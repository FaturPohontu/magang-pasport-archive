<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $rolePengguna = strtolower((string) session()->get('role'));
        $roleDiizinkan = $arguments ?? [];

        if (! in_array($rolePengguna, $roleDiizinkan, true)) {
            return redirect()->to('/dashboard')
                ->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        //
    }
}