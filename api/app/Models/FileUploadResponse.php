<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUploadResponse extends Model
{
    private $succeed;
    private $extension;
    private $path;
    private $request;
    private $lines;
    private $validHeader;
    private $validLines;
    private $linesCount;

    /**
     * @return mixed
     */
    public function getSucceed()
    {
        return $this->succeed;
    }

    /**
     * @param mixed $succeed
     */
    public function setSucceed($succeed): void
    {
        $this->succeed = $succeed;
    }

//    public function __toString()
//    {
//        $values = array(
//            'succeed' => $this->getSucceed(),
//            'path' => $this->getPath(),
//            'extension' => $this->getExtension(),
//            'lines' => $this->getLines(),
//            'headerValid' => $this->getValidHeader()
//        );
//
//        $response = array(
//            'data' => $values
//        );
//        return json_encode($response);
//    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param mixed $lines
     */
    public function setLines($lines): void
    {
        $this->lines = $lines;
    }

    /**
     * @return mixed
     */
    public function getValidHeader()
    {
        return $this->validHeader;
    }

    /**
     * @param mixed $validHeader
     */
    public function setValidHeader($validHeader): void
    {
        $this->validHeader = $validHeader;
    }

    /**
     * @return mixed
     */
    public function getValidLines()
    {
        return $this->validLines;
    }

    /**
     * @param mixed $validLines
     */
    public function setValidLines($validLines): void
    {
        $this->validLines = $validLines;
    }

    /**
     * @return mixed
     */
    public function getLinesCount()
    {
        return $this->linesCount;
    }

    /**
     * @param mixed $linesCount
     */
    public function setLinesCount($linesCount): void
    {
        $this->linesCount = $linesCount;
    }
}
