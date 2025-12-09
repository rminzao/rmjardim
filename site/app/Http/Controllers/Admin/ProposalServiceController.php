<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ProposalService;
use App\Models\Admin\ProposalServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProposalServiceController extends Controller
{
    // Página web do painel admin
    public function indexWeb()
    {
        return view('admin.proposals.index');
    }

    // Listar todas as propostas
    public function index()
    {
        $proposals = ProposalService::with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($proposals);
    }

    // Criar nova proposta
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string',
            'client_phone' => 'required|string',
            'client_address' => 'required|string',
            'services' => 'required|array|min:1',
            'services.*.description' => 'required|string',
            'services.*.unit' => 'required|in:un,m²,m,hr,dia',
            'services.*.quantity' => 'required|numeric|min:0',
            'services.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validar CPF se fornecido
        if ($request->client_document && $request->client_document_type === 'cpf') {
            if (!$this->validarCPF($request->client_document)) {
                return response()->json([
                    'message' => 'CPF inválido'
                ], 422);
            }
        }

        // Validar e buscar dados do CNPJ se fornecido
        if ($request->client_document && $request->client_document_type === 'cnpj') {
            $cnpjData = $this->consultarCNPJ($request->client_document);
            
            if (!$cnpjData) {
                return response()->json([
                    'message' => 'CNPJ inválido ou não encontrado'
                ], 422);
            }
        }

        // Criar proposta
        $proposal = ProposalService::create([
            'client_name' => $request->client_name,
            'client_document' => $request->client_document,
            'client_document_type' => $request->client_document_type,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'client_address' => $request->client_address,
            'client_city' => $request->client_city,
            'client_state' => $request->client_state,
            'client_zipcode' => $request->client_zipcode,
            'company_name' => $request->company_name,
            'company_trade_name' => $request->company_trade_name,
            'subtotal' => 0,
            'discount' => $request->discount ?? 0,
            'total' => 0,
            'payment_conditions' => $request->payment_conditions ?? '50% entrada + 50% na conclusão',
            'validity_days' => $request->validity_days ?? 15,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        // Criar itens
        $subtotal = 0;
        foreach ($request->services as $index => $service) {
            $total = $service['quantity'] * $service['unit_price'];
            $subtotal += $total;

            ProposalServiceItem::create([
                'proposal_service_id' => $proposal->id,
                'description' => $service['description'],
                'unit' => $service['unit'],
                'quantity' => $service['quantity'],
                'unit_price' => $service['unit_price'],
                'total' => $total,
                'order' => $index,
            ]);
        }

        // Atualizar totais
        $proposal->subtotal = $subtotal;
        $proposal->total = $subtotal - $proposal->discount;
        $proposal->save();

        return response()->json([
            'message' => 'Proposta criada com sucesso',
            'proposal' => $proposal->load('items')
        ], 201);
    }

    // Buscar dados do CNPJ na Receita Federal
    public function consultarCNPJ($cnpj)
    {
        // Limpar CNPJ
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        try {
            // API gratuita da Receita Federal
            $response = Http::timeout(10)->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'company_name' => $data['razao_social'] ?? null,
                    'company_trade_name' => $data['nome_fantasia'] ?? null,
                    'client_address' => ($data['logradouro'] ?? '') . ', ' . ($data['numero'] ?? ''),
                    'client_city' => $data['municipio'] ?? null,
                    'client_state' => $data['uf'] ?? null,
                    'client_zipcode' => $data['cep'] ?? null,
                    'client_phone' => $data['ddd_telefone_1'] ?? null,
                    'client_email' => $data['email'] ?? null,
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Endpoint para consultar CNPJ (chamado pelo frontend)
    public function getCNPJData($cnpj)
    {
        $data = $this->consultarCNPJ($cnpj);

        if (!$data) {
            return response()->json([
                'message' => 'CNPJ não encontrado'
            ], 404);
        }

        return response()->json($data);
    }

    // Validar CPF
    private function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    // Endpoint para validar CPF (chamado pelo frontend)
    public function validateCPF(Request $request)
    {
        $cpf = $request->input('cpf');
        
        $valid = $this->validarCPF($cpf);

        return response()->json([
            'valid' => $valid
        ]);
    }

    // Visualizar proposta
    public function show($id)
    {
        $proposal = ProposalService::with('items')->findOrFail($id);

        return response()->json($proposal);
    }

    // Atualizar proposta
    public function update(Request $request, $id)
    {
        $proposal = ProposalService::findOrFail($id);

        $proposal->update($request->only([
            'client_name',
            'client_phone',
            'client_email',
            'client_address',
            'discount',
            'payment_conditions',
            'validity_days',
            'notes',
            'status',
        ]));

        // Atualizar total se desconto mudou
        if ($request->has('discount')) {
            $proposal->total = $proposal->subtotal - $request->discount;
            $proposal->save();
        }

        return response()->json([
            'message' => 'Proposta atualizada com sucesso',
            'proposal' => $proposal->load('items')
        ]);
    }

    // Deletar proposta
    public function destroy($id)
    {
        $proposal = ProposalService::findOrFail($id);
        $proposal->delete();

        return response()->json([
            'message' => 'Proposta excluída com sucesso'
        ]);
    }

    // Gerar PDF
    public function generatePDF($id)
    {
        $proposal = ProposalService::with('items')->findOrFail($id);
        
        $pdfGenerator = new \App\Services\ProposalPDFGenerator();
        $path = $pdfGenerator->generate($proposal);

        return response()->json([
            'message' => 'PDF gerado com sucesso',
            'path' => $path,
            'url' => asset('storage/' . $path)
        ]);
    }

    // Download do PDF
    public function downloadPDF($id)
    {
        $proposal = ProposalService::with('items')->findOrFail($id);
        
        $pdfGenerator = new \App\Services\ProposalPDFGenerator();
        
        return $pdfGenerator->download($proposal);
    }
}