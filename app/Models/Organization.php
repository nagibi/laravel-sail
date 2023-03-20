<?php

namespace ProjectNpx;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    protected $fillable = [
        'description',
        'comercial_responsible_name',
        'comercial_phone_number',
        'name',
        'ans_number',
        'unit_of_measure',
        'comercial_price',
        'image_file_name',
        'last_updated_by',
        'created_by',
        'validate_document_duplicity_flag',
        'check_ip_address_flag',
        'mfa_flag',
        'mfa_check_ip_adress_flag',
        'use_billing_report_flag',
        'replace_rac_protocol_number',
        'replace_rac_protocol_number_bl',
        'replace_rac_batch_number_bl',
        'refute_auto_release_flag',
        'days_to_refute_auto_release',
        'required_refute_reason_flag',
        'document_response_flag',
        'authorization_response_flag',
        'export_loss_xml',
        'use_document_in_rac_xml',
        'automated_glosa_analysis_flag',
        'from_to_procedures_flag',
        'integration_reconcile_erp_flag',
        'saml2_idp',
    ];

    public function user_created(){
        return $this->belongsTo('ProjectNpx\User', 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo('ProjectNpx\User', 'last_updated_by');
    }
}
