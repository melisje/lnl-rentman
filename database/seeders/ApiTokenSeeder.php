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
                "api_token"     => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZWRld2Vya2VyIjo1OTQsImFjY291bnQiOiJsbHN0YWdlc2VydmljZSIsImNsaWVudF90eXBlIjoib3BlbmFwaSIsImNsaWVudC5uYW1lIjoib3BlbmFwaSIsImV4cCI6MTg1NjE3MzkyNCwiaXNzIjoie1wibmFtZVwiOlwiYmFja2VuZFwiLFwidmVyc2lvblwiOlwiNC41ODQuMC4xXCJ9IiwiaWF0IjoxNjk4MzIxMTI0fQ.IrZfEugnBoo0DV3e1DGViJp8_fNkI7owasG5zZH-xis",
                "webhook_token" => "mCqKqJxSM3FLEBquOKbIkGoabc7NONSJl38d92ODk30H6mgj8l91183Baab3eLrL8qO0xwuL1Sm1RJiOznLymwJojC5qkDFSwRutLbyKi7N8DDMlNy6In0M5bhJ5eKFm5PnLNENMzMJHkBPD3PQ6D8BGnpGcF0vNdaO3oq560FcO09ASRj211RBG1FNOrcqI9Oz8PnrdRxCwBILv1D2ine1pxcnd7h7KEMHDm1qg5HejtdcqJmn377wmiKbdzww5",
                "url" => "https://api.rentman.net"
            ]);

        $token = ApiToken::updateOrCreate(
            ["account" => "ledvisions"],
            [
                "api_token"     => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZWRld2Vya2VyIjoyMzUsImFjY291bnQiOiJsZWR2aXNpb25zIiwiY2xpZW50X3R5cGUiOiJvcGVuYXBpIiwiY2xpZW50Lm5hbWUiOiJvcGVuYXBpIiwiZXhwIjoyMDY1OTQzODQ2LCJpc3MiOiJ7XCJuYW1lXCI6XCJiYWNrZW5kXCIsXCJ2ZXJzaW9uXCI6XCI0Ljc1NC4wLjFcIn0iLCJpYXQiOjE3NTA0MTEwNDZ9.Hhf8qpROUkJbLIlpfcGGKPVAVda-ksBPGszCaaB0HPc",
                "webhook_token" => "eDv8pxhtfawkmS0nJSOKiN76HGJyk39csaQIHSBz9xluiJD3FeIlsOEv27bJ1N2PxSajy8iRaumgD7mQzcNGOxrJq05yAs55JgE8fCtdMoCPinbbObJEn1p3NwSQlrzmbL27NGua41NaPMhkolj4ba538lds7aEnkbcpGgeQtH8nyf79mNJ4y0atPiKpqIxQNAqJPaykH7tLp6hjuwBOQ0oJrF21Gz3wCd1CF01ElzSq01hzj44gk6IvEc7l78j4",
                "url" => "https://api.rentman.net"
            ]);

        $token = ApiToken::updateOrCreate(
            ["account" => "rentmantest"],
            [
                "api_token"     => "",
                "webhook_token" => "",
                "url" => "http://rentmant.test"
            ]);
    }
}
