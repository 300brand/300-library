<?php

declare(strict_types=1);

namespace ThreeHundred\Library\Form;

use ArrayAccess;
use ArrayIterator;
use BadMethodCallException;
use InvalidArgumentException;
use IteratorAggregate;
use Traversable;
use Psr\Http\Message\UploadedFileInterface;

use const UPLOAD_ERR_CANT_WRITE;
use const UPLOAD_ERR_EXTENSION;
use const UPLOAD_ERR_FORM_SIZE;
use const UPLOAD_ERR_INI_SIZE;
use const UPLOAD_ERR_NO_FILE;
use const UPLOAD_ERR_NO_TMP_DIR;
use const UPLOAD_ERR_OK;
use const UPLOAD_ERR_PARTIAL;

class Form implements ArrayAccess, IteratorAggregate
{
    private const ERROR_MESSAGES = [
        UPLOAD_ERR_OK         => 'There is no error, the file uploaded with '
            . 'success',
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the '
            . 'upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE '
            . 'directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially '
            . 'uploaded',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];

    private $data = [];
    private $filters = [];
    private $input = [];
    private $uploads = [];

    public function __construct(
        array $filters,
        array $input,
        array $uploads = []
    ) {
        $this->filters = $this->normalizeFilters($filters);
        $this->input = $input;
        $this->data = $this->filterInput();
        $this->uploads = $uploads;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->filters as $key => $filter) {
            $value = $this->data[$key];

            // Flag empty values on required keys
            $required = $filter['required'] ?? true;
            if ($required && empty($value)) {
                $errors[$key] = 'Value required';
            }

            // Determine what failed FILTER_VALIDATE_* filters
            $isBoolFilter = $filter['filter'] == FILTER_VALIDATE_BOOLEAN;
            if (!$isBoolFilter && $value === false) {
                $errors[$key] = 'Invalid value';
            }

            // Call user-defined validator
            $validate = $filter['validate'] ?? null;
            if ($validate !== null) {
                $error = call_user_func($validate, $value, $this->data);
                if ($error !== null) {
                    $errors[$key] = $error;
                }
            }
        }

        foreach ($this->uploads as $key => $upload) {
            // Leave multifile upload processing to the implementor as there
            // are too many edge cases to consider at this time.
            if (is_array($upload)) {
                continue;
            }

            // Ensure uploads are the correct instance before accessing the
            // getError method
            if (!$upload instanceof UploadedFileInterface) {
                throw new InvalidArgumentException(
                    'Uploads must implement ' . UploadedFileInterface::class
                );
            }

            // Not checking for UPLOAD_ERR_NO_FILE to follow the rest of the
            // fields and default to required
            if ($upload->getError() != UPLOAD_ERR_OK) {
                $errors[$key] = self::ERROR_MESSAGES[$upload->getError()];
            }
        }
        return $errors;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->data)
            || array_key_exists($offset, $this->uploads);
    }

    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->data)) {
            return $this->data[$offset];
        }
        if (array_key_exists($offset, $this->uploads)) {
            return $this->uploads[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('Form values are readonly');
    }

    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('Form values are readonly');
    }

    private function filterInput(): array
    {
        return filter_var_array($this->input, $this->filters);
    }

    private function normalizeFilters(array $filters): array
    {
        foreach ($filters as $key => $filter) {
            if (is_scalar($filter)) {
                $filters[$key] = ['filter' => $filter];
            }
        }
        return $filters;
    }
}
