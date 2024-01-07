<?php 
class DateResponse {
    public String $value;
    public String $formattedDate;
    public function __construct(String $value, String $formattedDate) {
        $this->value = $value;
        $this->formattedDate = $formattedDate;
    }
}