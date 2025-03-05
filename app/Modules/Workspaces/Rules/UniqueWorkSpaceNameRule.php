<?php

namespace App\Modules\Workspaces\Rules;

use App\Modules\Workspaces\Workspace;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueWorkSpaceNameRule implements ValidationRule
{

    public $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $query = Workspace::where('name', $value);
            if ($this->id) {
                $query = $query->where('id', '<>', $this->id);
            }
            $validation =  $query->exists();

            if ($validation) {
                $fail("The name of workspace is taken.");
            }
        } catch (\Exception $e) {
            $fail('bad format');
        }
    }
}
