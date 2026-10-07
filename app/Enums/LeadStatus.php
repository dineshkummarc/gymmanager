<?php
namespace App\Enums;
enum LeadStatus: string { case New='new'; case Contacted='contacted'; case Interested='interested'; case Trial='trial'; case Proposal='proposal'; case Negotiation='negotiation'; case Won='won'; case Lost='lost';
    public function label(): string { return match($this){ self::New=>'Novo', self::Contacted=>'Contatado', self::Interested=>'Interessado', self::Trial=>'Experimental', self::Proposal=>'Proposta', self::Negotiation=>'Negociação', self::Won=>'Ganho', self::Lost=>'Perdido' }; } }
