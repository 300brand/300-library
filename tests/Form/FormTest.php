<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Tests\Form;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use ThreeHundred\Library\Form\Form;

final class FormTest extends TestCase
{
    public function testFilterValueCast(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
            'b' => FILTER_VALIDATE_BOOLEAN,
        ];
        $data = ['a' => '1', 'b' => '1'];
        $form = new Form($filters, $data);
        $this->assertEquals(1, $form['a']);
        $this->assertTrue($form['b']);
    }

    public function testBadKeyArrayAccess(): void
    {
        $form = new Form(['a' => FILTER_VALIDATE_INT], ['a' => 1]);
        $this->assertNull($form['b']);
    }

    public function testCannotSetViaArrayAccess(): void
    {
        $form = new Form(['a' => FILTER_VALIDATE_INT], ['a' => 1]);

        $this->expectException(BadMethodCallException::class);
        $form['b'] = 2;
    }

    public function testCannotUnsetViaArrayAccess(): void
    {
        $form = new Form(['a' => FILTER_VALIDATE_INT], ['a' => 1]);

        $this->expectException(BadMethodCallException::class);
        unset($form['a']);
    }

    public function testGetData(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
            'b' => FILTER_VALIDATE_INT,
        ];
        $data = ['a' => 1, 'b' => 2];
        $form = new Form($filters, $data);

        $this->assertEquals($data, $form->getData());
    }

    public function testIterator(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
            'b' => FILTER_VALIDATE_INT,
        ];
        $data = ['a' => 1, 'b' => 2];
        $form = new Form($filters, $data);

        $out = [];
        foreach ($form as $key => $value) {
            $out[$key] = $value;
        }

        $this->assertEquals($data, $out);
    }

    public function testEmptyErrors(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
        ];
        $data = ['a' => '1'];
        $form = new Form($filters, $data);
        $errors = $form->getErrors();
        $this->assertEmpty($errors);
    }

    public function testValueRequiredError(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
        ];
        $data = [];
        $form = new Form($filters, $data);
        $errors = $form->getErrors();
        $this->assertArrayHasKey('a', $errors);
        $this->assertEquals('Value required', $errors['a']);
    }

    public function testValueInvalidError(): void
    {
        $filters = [
            'a' => FILTER_VALIDATE_INT,
        ];
        $data = ['a' => 'abc'];
        $form = new Form($filters, $data);
        $errors = $form->getErrors();
        $this->assertArrayHasKey('a', $errors);
        $this->assertEquals('Invalid value', $errors['a']);
    }

    public function testValueNotRequiredError(): void
    {
        $filters = [
            'a' => [
                'filter' => FILTER_VALIDATE_INT,
                'required' => false,
            ],
        ];
        $data = [];
        $form = new Form($filters, $data);
        $errors = $form->getErrors();
        $this->assertEmpty($errors);
    }

    public function testValidateFunction(): void
    {
        $validate = function ($value, array $values): ?string {
            if ($value == 1) {
                return 'Value is 1';
            }
            return null;
        };
        $filters = [
            'a' => [
                'filter'   => FILTER_VALIDATE_INT,
                'validate' => $validate,
            ],
            'b' => [
                'filter'   => FILTER_VALIDATE_INT,
                'validate' => $validate,
            ],
        ];
        $data = ['a' => 1, 'b' => 2];
        $form = new Form($filters, $data);
        $errors = $form->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('a', $errors);
        $this->assertEquals('Value is 1', $errors['a']);
    }
}
