<?php
namespace kisa7081;

use DWA\Form;

class MyForm extends Form
{

    /*
     * Override of validate - modified for the $fieldsToValidate
     * to be of the form:
     *
     *      [
                ['field1_name', 'field1_label', 'validations'],
                ['field2_name', 'field2_label', 'validations'],
                   ...
            ]);
     */
    public function validate(array $fieldsToValidate)
    {
        $errors = [];

        foreach ($fieldsToValidate as $fields) {
            # Each rule is separated by a |
            $rules = explode('|', $fields[2]);
            foreach ($rules as $rule) {
                # Get the value for this field from the request
                $value = $this->get($fields[0]);

                # Handle any parameters with the rule, e.g. max:99
                $parameter = null;
                if (strstr($rule, ':')) {
                    list($rule, $parameter) = explode(':', $rule);
                }

                # Run the validation test with the given rule
                $test = $this->$rule($value, $parameter);

                # Test failed
                if (!$test) {
                    $method = $rule . 'Message';
                    $errors[] = 'The value for ' . $fields[1] . ' ' . $this->$method($parameter) . '.';

                    # Only indicate one error per field
                    break;
                }
            }
        }

        # Set public property hasErrors as Boolean
        $this->hasErrors = !empty($errors);

        return $errors;
    }

}