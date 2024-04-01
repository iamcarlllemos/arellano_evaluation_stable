<?php

namespace App\Livewire\Admin;

use chillerlan\QRCode\QRCode;
use Livewire\Component;
use Livewire\WithFileUploads;

class ValidateResponses extends Component
{

    use WithFileUploads;

    public $form;
    public $type;
    public $code;

    public function submit() {

        if($this->type == 1) {

        } else if($this->type == 2) {
            $rule = [
                'code' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            ];

            $this->validate($rule);

            $reference = $this->decode_qr($this->code->path());

        }
    }

    public function decode_qr($image) {
        try {
            $qr = new QRCode();
            $string = $qr->readFromFile($image);
            return $string->data;
        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function render()
    {
        return view('livewire.admin.validate-responses');
    }

}
