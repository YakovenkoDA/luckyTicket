<?php
//родительский класс
abstract class LuckyTicket{   
    
    protected $lengs;
    protected $count;
    
    public function getLengs(){return $this->lengs*2;}
    public function  getCount(){return $this->count;}
    
    //конструктор с проверкой значения длины и вызовом абстрактных методов
    public function __construct($lengs=6) {
        if($lengs<2){
         echo'<p>sorry variable must be more 2</p>';   
         self::__construct(2);         
        }else{
            $this->lengs= floor($lengs/2);
            $this->create();
            $this->countTickets();        
        }
    }
    abstract function create();
    abstract function countTickets();    
}

//наследуемый класс
//подсчет методом перебора 
final class  LuckyNumbers  extends LuckyTicket{
    
    private $leftPart;
    private $rightPart;
    private $luckyNumbers=array();
    
    public function  getLuckyNumbers(){return $this->luckyNumbers;}
    
    public function create(){ //генерирует 2 части с заданными числами       
        $this->leftPart=str_repeat('9',$this->lengs);
        $this->rightPart=str_repeat('9',$this->lengs);        
    }
    
    public function countTickets(){//цикл перебора элеменнтов и их сравнение
        for($i=$this->leftPart;$i!=-1;$i--){
            $iSum=$this->sum(strval($i));
            for($j=$this->rightPart;$j!=-1;$j--){
               if($iSum==$this->sum(strval($j))){
                   $format='%0'.  $this->lengs.'u';
                   array_push($this->luckyNumbers, sprintf($format,$i).sprintf($format,$j));//сохраняет счасливый номер
                   $this->count++;
               } 
            }
        }        
    }
    
    private function sum($part){//сумирует цифры переданой части
        $sum=0;
        $strlengs=strlen($part);
        for($i=0;$i<$strlengs;$i++){
            $sum+=$part[$i];            
        }
        return $sum;
    }
}

//наследуемый класс
//подсчет формулой
final class LuckyCount extends LuckyTicket{
    
    private $firstCounts=[1,1,1,1,1,1,1,1,1,1];
    private $lastCounts;
    
    public function create($array=1,$iterator=1) {//создает масив значений
        if($iterator==1){$data=$this->firstCounts;}
        else{
        $dataLength = $iterator*9+1;             
        $arrCount=count($array);       
        $sum = 0;                       
            for ($i = 0;$i < $dataLength; $i++) {
                if($i<$arrCount){$sum += $array[$i];}
                if ($i >= 10){$sum -= $array[$i - 10];}
                $data[$i] = $sum;
                }        
            }            
            if($iterator==$this->lengs){$this->lastCounts=$data;}
            else{$this->create($data, ++$iterator);}
    }    
    
    public function countTickets() {//подсчитывает квадрат значений из масива
      for ($i = 0; $i <= $this->lengs * 9; $i++) {
          $this->count += $this->lastCounts[$i] * $this->lastCounts[$i];
          
      }      
    }
 }

//вывод значений
$check=new LuckyCount(4);
echo 'clas LuckyCount '. $check->getCount().'</br>';

$check=new LuckyNumbers(3);
echo '<pre>';
print_r($check->getLuckyNumbers());
echo '</pre>';
echo 'clas LuckyNumbers '.$check->getCount().'</br>';