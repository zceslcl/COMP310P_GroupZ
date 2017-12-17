<?php

class Page{
    public $page = 1; //current page
    public $pageSize=0; //page size
    public $maxPage=0; //total page
    public $maxRows=0; //total row
    public $url=null; //current url
    public $urlParam=null; //current parameter in url


    public function __construct($maxRows,$pageSize=10){
        $this->maxRows = $maxRows;
        $this->pageSize = $pageSize;
        $this->page = isset($_GET['p'])?$_GET['p']:1; //get current page
        $this->url = $_SERVER["PHP_SELF"]; //get current page url

        //get the total number of the page
        $this->getMaxPage();
        //check the current value of the page
        $this->checkPage();

        $this->filterUrlParam();

    }

    //calculate total page
    private function getMaxPage(){
        $this->maxPage = ceil($this->maxRows/$this->pageSize);
    }

    //check current value
    private function checkPage(){
        if($this->page>$this->maxPage){
            $this->page=$this->maxPage;
        }
        if($this->page<1){
            $this->page=1;
        }
    }


    private function filterUrlParam(){

        foreach($_GET as $k=>$v){

           if($v!="" && $k!="p"){
              $this->urlParam.="&{$k}={$v}";
           }
        }
    }


    public function limit(){
        return (($this->page-1)*$this->pageSize).",".$this->pageSize;
    }

    //output page number
    public function showPage(){
        $str = '';
        $str.="Current page: {$this->page}/{$this->maxPage} &nbsp &nbsp Total entries: {$this->maxRows} ";
        $str.=" <a href='{$this->url}?p=1{$this->urlParam}'>&nbsp &nbsp First page</a> ";
        $str.=" <a href='{$this->url}?p=".($this->page-1)."{$this->urlParam}'>&nbsp &nbsp Previous page</a> ";
        $str.=" <a href='{$this->url}?p=".($this->page+1)."{$this->urlParam}'>&nbsp &nbsp Next page</a> ";
        $str.=" <a href='{$this->url}?p={$this->maxPage}{$this->urlParam}'>&nbsp &nbsp Last page</a> ";
        return $str;
    }
}