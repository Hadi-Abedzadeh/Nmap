<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NmapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('modules.nmap.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $hostname)
    {
        /*** MOCK DATA */

        $data = [
            '127.0.0.1:::Linux 2.6.32',
            '127.0.0.2:::Linux 2.6.32',
        ];

        $processed = collect($data)->map(function ($item, $key) {
            [$ip, $os] = explode(':::', $item);

            return [
                'id' => $key + 1,
                'ip' => $ip,
                'os' => $os,
                'radio' => "<input type='checkbox' value='nmap[{$key}][{$ip}][{$os}]'>",
            ];
        });

        return json_encode(($processed), JSON_PRETTY_PRINT);

        /*** END MOCK */
        header('Content-Type: application/json');

//        $command = getenv("COMMAND_PRIVILEGE")." nmap -A -O 127.0.0.1 | grep 'OS details' | awk -v ip=\"127.0.0.1\" '{print ip \": \" $0}' ";
        $command = "ping {$hostname}";
        $output = [];
        exec($command, $output);

        // remove blank lines
        $output = array_filter($output, function ($line) {
            return trim($line) !== "";
        });

        return json_encode(array_values($output), JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
