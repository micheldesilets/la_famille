<?php

declare(strict_types=1); // strict mode

namespace priv\php\classes\business;

class Photos implements \JsonSerializable
{
    private $m_idpho;
    private $m_title;
    private $m_keywords;
    private $m_caption;
    private $m_f_path;
    private $m_p_path;
    private $m_filename;
    private $m_pdf;
    private $m_geneolIdx;
    private $m_geneolNames;
    private $m_rptTitle;
    private $m_year;

    public function set_Idpho($idpho)
    {
        $this->m_idpho = $idpho;
    }

    function get_idpho()
    {
        return $this->m_idpho;
    }

    public function set_Title($title)
    {
        $this->m_title =$title;
    }

    function get_Title()
    {
        return $this->m_title;
    }

    public function set_Keywords($keywords)
    {
        $this->m_keywords = $keywords;
    }

    function get_keywords()
    {
        return $this->m_keywords;
    }

    public function set_Height($height)
    {
        $this->m_height = $height;
    }

    function get_Height()
    {
        return $this->m_height;
    }

    public function set_Width($width)
    {
        $this->m_width = $width;
    }

    function get_Width()
    {
        return $this->m_width;
    }

    public function set_Caption($caption)
    {
        $this->m_caption = $caption;
    }

    function get_Caption()
    {
        return $this->m_caption;
    }

    public function set_F_Path($path)
    {
        $this->m_f_path = $path;
    }

    function get_F_Path()
    {
        return $this->m_f_path;
    }

    public function set_P_Path($path)
    {
        $this->m_p_path = $path;
    }

    function get_P_Path()
    {
        return $this->m_p_path;
    }

    /**
     * @param $filename
     */
    public function set_Filename($filename)
    {
        $this->m_filename = str_replace(' ', '_', $filename);
    }

    function get_Filename()
    {
        return $this->m_filename;
    }

    /**
     * @param mixed $m_pdf
     */
    public function set_Pdf($m_pdf): void
    {
        $this->m_pdf = $m_pdf;
    }

    /**
     * @return mixed
     */
    public function get_Pdf()
    {
        return $this->m_pdf;
    }

    /**
     * @param mixed $m_geneol
     */
    public function set_GeneolIdx($m_geneolIdx): void
    {
        $this->m_geneolIdx = $m_geneolIdx;
    }

    /**
     * @return mixed
     */
    public function get_GeneolIdx()
    {
        return $this->m_geneolIdx;
    }

    /**
     * @return mixed
     */
    public function get_GeneolNames()
    {
        return $this->m_geneolNames;
    }

    /**
     * @param mixed $m_geneolNames
     */
    public function set_GeneolNames($m_geneolNames): void
    {
        $this->m_geneolNames = $m_geneolNames;
    }

    /**
     * @param mixed $m_rptTitle
     */
    public function set_rptTitle($m_rptTitle): void
    {
        $this->m_rptTitle = $m_rptTitle;
    }

    /**
     * @return mixed
     */
    /**
     * @return mixed
     */
    public function get_rptTitle()
    {
        return $this->m_rptTitle;
    }

    /**
     * @param mixed $m_year
     */
    public function set_Year($m_year): void
    {
        $this->m_year = $m_year;
    }

    /**
     * @return mixed
     */
    public function get_Year()
    {
        return $this->m_year;
    }

    public function jsonSerialize()
    {
        return [
            'idpho' => $this->get_idpho(),
            'title' => $this->get_Title(),
            'keywords' => $this->get_Keywords(),
            'caption' => $this->get_Caption(),
            'path' => $this->get_F_Path(),
            'prev_path' => $this->get_P_Path(),
            'filename' => $this->get_Filename(),
            'pdf_pho' => $this->get_Pdf(),
            'geneolidx' => $this->get_GeneolIdx(),
            'geneolnames' => $this->get_GeneolNames(),
            'rptTitle' => $this->get_rptTitle(),
            'year' => $this->get_Year()
        ];
    }

}
