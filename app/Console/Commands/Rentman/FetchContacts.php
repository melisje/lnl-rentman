<?php

namespace App\Console\Commands\Rentman;

use App\Models\Rentman\Contact;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FetchContacts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:fetch-contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch contacts from Rentman API';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanApi)
    {
        $this->info("RENTMAN");
        $this->info("Start fetching contact");

        $endpoint = 'contacts';

        $filters = [
            'modified[gte]' => '2025-01',
            // 'id[gte]' => '8440',
            'id' => '8052',
            // 'name[gte]' => 'RFID',
        ];

        $timestampFields = [
            'created',
            'modified',
        ];

        $fk_fields = [
            'creator',
            'folder',
        ];

        $fields = array_merge([
            // 'id',
            'created',
            'modified',
            'displayname',
            'type',
            'ext_name_line',
            'firstname',
            'distance',
            'travel_time',
            'surfix',
            'surname',
            'longitude',
            'latitude',
            'code',
            'accounting_code',
            'name',
            // 'gender',
            'mailing_city',
            'mailing_street',
            'mailing_number',
            'mailing_postalcode',
            'mailing_state',
            'mailing_country',
            'visit_city',
            'visit_street',
            'visit_number',
            'visit_postalcode',
            'visit_state',
            'country',
            'invoice_city',
            'invoice_street',
            'invoice_number',
            'invoice_postalcode',
            'invoice_state',
            'invoice_country',
            'phone_1',
            'phone_2',
            'email_1',
            'email_2',
            'website',
            'VAT_code',
            'fiscal_code',
            'commerce_code',
            'purchase_number',
            'bic',
            'bank_account',
            'default_person',
            'admin_contactperson',
            'discount_crew',
            'discount_transport',
            'discount_rental',
            'discount_sale',
            'discount_total',
            'projectnote',
            'projectnote_title',
            'contact_warning',
            'discount_subrent',
            'image',
            'tags',
            'updateHash',
            'custom_9',
        ], $timestampFields,$fk_fields);

        $queryParameters = [
            'fields' => implode(',', $fields),
            'sort' => "-modified",
            'limit' => 200,
        ];


        // Add filters to $queryParameters
        foreach ($filters as $key => $value) {
            $queryParameters[$key] = $value;
        }

        $this->info(json_encode($queryParameters, JSON_PRETTY_PRINT));


        $contacts = $rentmanApi->getEndpointData($endpoint, $queryParameters);

        $this->info(count($contacts) . " projects found");


        $this->info(json_encode($contacts, JSON_PRETTY_PRINT));

        foreach ($contacts as $key => $contact) {
            // parse timestamp fields
            foreach($timestampFields as $field)
            {
                $contact[$field] = Carbon::parse($contact[$field]);
            }

            // parse fk_fields
            foreach($fk_fields as $field)
            {
                $contact[$field] = Str::afterLast($contact[$field],'/');
            }


            // create or update record in database
            Contact::upsert($contact, uniqueBy: ['id'], update: $fields);
        }
    }
}
