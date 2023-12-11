<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProrrogacoesEResgates extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $chequesProrrogados;
    public $antigosAdiamentos;
    public $parceiro_id;
    public $data;

    public function __construct($cheques, $antigosAdiamentos, $parceiro_id, $data)
    {
        $this->chequesProrrogados = $cheques;
        $this->antigosAdiamentos = $antigosAdiamentos;
        $this->parceiro_id = $parceiro_id;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.prorrogacao')
            ->subject('PRORROGAÇÕES DL - ' . date("d/m/Y", strtotime($this->data)))
            ->from('dudolucio@hotmail.com');
    }
}
