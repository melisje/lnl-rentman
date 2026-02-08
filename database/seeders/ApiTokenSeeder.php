<?php

namespace Database\Seeders;

use App\Models\Rentman\ApiToken;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApiTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add api_tokens for account llstageservice and ledvisions as known on 2026-02-07
        $token = ApiToken::updateOrCreate(
            ["account" => "llstageservice"],
            [
                "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZWRld2Vya2VyIjo1OTQsImFjY291bnQiOiJsbHN0YWdlc2VydmljZSIsImNsaWVudF90eXBlIjoib3BlbmFwaSIsImNsaWVudC5uYW1lIjoib3BlbmFwaSIsImV4cCI6MTg1NjE3MzkyNCwiaXNzIjoie1wibmFtZVwiOlwiYmFja2VuZFwiLFwidmVyc2lvblwiOlwiNC41ODQuMC4xXCJ9IiwiaWF0IjoxNjk4MzIxMTI0fQ.IrZfEugnBoo0DV3e1DGViJp8_fNkI7owasG5zZH-xis",
                "url" => "https://api.rentman.net"
            ]);

        $token = ApiToken::updateOrCreate(
            ["account" => "ledvisions"],
            [
                "token" => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZWRld2Vya2VyIjoyMzUsImFjY291bnQiOiJsZWR2aXNpb25zIiwiY2xpZW50X3R5cGUiOiJvcGVuYXBpIiwiY2xpZW50Lm5hbWUiOiJvcGVuYXBpIiwiZXhwIjoyMDY1OTQzODQ2LCJpc3MiOiJ7XCJuYW1lXCI6XCJiYWNrZW5kXCIsXCJ2ZXJzaW9uXCI6XCI0Ljc1NC4wLjFcIn0iLCJpYXQiOjE3NTA0MTEwNDZ9.Hhf8qpROUkJbLIlpfcGGKPVAVda-ksBPGszCaaB0HPc",
                "url" => "https://api.rentman.net"
            ]);

        $token = ApiToken::updateOrCreate(
            ["account" => "rentmantest"],
            [
                "token" => "no token needed",
                "url" => "http://rentmant.test"
            ]);
    }
}
