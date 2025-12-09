<?php

namespace App\Services;

use App\Models\Admin\ProposalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ProposalPDFGenerator
{
    public function generate(ProposalService $proposal)
    {
        $proposal->load('items');

        $pdf = Pdf::loadView('pdf.proposal', [
            'proposal' => $proposal
        ]);

        $pdf->setPaper('a4', 'portrait');

        $filename = 'proposta-' . substr($proposal->id, 0, 8) . '-' . str_replace(' ', '-', $proposal->client_name) . '.pdf';
        
        $path = 'proposals/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());

        $proposal->pdf_path = $path;
        $proposal->save();

        return $path;
    }

    public function download(ProposalService $proposal)
    {
        if (!$proposal->pdf_path || !Storage::disk('public')->exists($proposal->pdf_path)) {
            $this->generate($proposal);
        }

        return Storage::disk('public')->download($proposal->pdf_path);
    }
}