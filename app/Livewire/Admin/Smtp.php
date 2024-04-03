<?php

namespace App\Livewire\Admin;

use App\Models\SmtpModel;
use Livewire\Component;

class Smtp extends Component
{

    public $form;

    public $servername;
    public $port;
    public $username;
    public $password;

    public function update() {

        $rules = [
            'servername' => 'required_with:port,username,password',
            'port' => 'required_with:servername,username,password, integer',
            'username' => 'required_with:servername,port,password',
            'password' => 'required_with:servername,port,username',
        ];

        $this->validate($rules, [
            'servername.required_with' => 'Server name is required.',
            'port.required_with' => 'Port is required.',
            'username.required_with' => 'Username is required.',
            'password.required_with' => 'Password is required.',
        ], [
            'servername' => 'Server name',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password'
        ]);

        try {

            $model = SmtpModel::firstOrNew(['id' => 1]);
            $model->servername = $this->servername;
            $model->port = $this->port;
            $model->username = $this->username;
            $model->password = $this->password;
            $model->save();

            $this->dispatch('alert');
            session()->flash('alert', [
                'message' => 'Saved.'
            ]);

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function mount() {
        $data = SmtpModel::find(1);
        if($data != null) {
            $this->servername = $data->servername;
            $this->port = $data->port;
            $this->username = $data->username;
            $this->password = $data->password;
        }
    }


    public function render()
    {
        return view('livewire.admin.smtp');
    }
}
