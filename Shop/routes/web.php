<?php
// namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redirect', function (Request $request) {
    $query = http_build_query([
        'client_id' => 9,
        'redirect_uri' => 'http://localhost:8001/info-bank/callback',
        'response_type' => 'code',
        'scope' => '*',
    ]);

    return redirect('http://localhost:8000/oauth/authorize?' . $query);
})->name('info.bank');

Route::get('info-bank/callback', function (Request $request) {
    if ($request->has('error')) {
        return 'you dont access to this account';
    }

    // dd($request->all());
    $http = new GuzzleHttp\Client;
    $response = $http->post('http://localhost:8000/oauth/token', [
        'form_params' => [

            'grant_type' => 'authorization_code',
            'client_id' => 9,
            'client_secret' => 'Ap3cVfriNCiC7OABeRfUmtPo2wnVcZl2NYB7uDvO',
            'redirect_uri' => 'http://localhost:8001/info-bank/callback',
            'code' => $request->code,

        ],
        'exceptions' => false,
    ]);
    $accessToken = json_decode((string) $response->getBody(), true);
    dd($accessToken);
});
