<?
class bordro {
 private $brut_ucret;
 private $sgk_isci_payi;
 private $issizlik_sigortasi; 
 private $gelirmatrahi; 
 private $gelirvergisi; 
 private $damgavergisi; 
 private $agimatrahi;
 private $agi;
 private $odenecek_gelir_vergisi;
 private $kesintiler_toplami;
 private $net_ucret;
 private $bordro_turu; 
 private $cocuksayisi = 0;
 private $emekliMi;
 private $evliMi;
 private $sakatlikDerecesi = 0;
 private $esCalisiyorMu;

     public function __construct ($brut_ucret, $bordro_turu, $cocuksayisi, $emekliMi, $evliMi=false,$esCalisiyorMu = false,$sakatlikDerecesi){
      //tum hesaplama iþlemleri burda yapýlacak.
      $this->brut_ucret  = trim($brut_ucret);
      $this->sgk_isci_payi = ($brut_ucret /100) * 14;
      $this->issizlik_sigortasi = ($brut_ucret /100) * 1;
      $this->gelirmatrahi = $brut_ucret - ($this->sgk_isci_payi + $this->issizlik_sigortasi);
      $this->gelirvergisi = ($this->gelirmatrahi / 100) * 15;
      $this->damgavergisi = ($this->brut_ucret / 100) * 07.59;
      $this->evliMi = $evliMi;
      $this->cocuksayisi = $cocuksayisi;
      $this->esCalisiyorMu = $esCalisiyorMu;
      $this->agimatrahi = $this->AgiMatrahiHesapla();
      $this->agi = ($this->agimatrahi / 100) * 15;
      $this->odenecek_gelir_vergisi= $this->gelirvergisi - $this->agi;
      $this->kesintiler_toplami = $this->sgk_isci_payi + $this->issizlik_sigortasi + $this->damgavergisi + $this->odenecek_gelir_vergisi;
      $this->net_ucret = $this->brut_ucret - $this->kesintiler_toplami;
     }
     
     public function getNetMaas(){
        return $this->net_ucret;
     }
     
     private function AgiMatrahiHesapla(){
        $temp = 0;
        $temp += ($this->brut_ucret / 100) * 50; //kendisi icin %50
        if($this->evliMi && !$this->esCalisiyorMu){
            $temp += ($this->brut_ucret /100) * 10; //calismayan es icin %10
        }
        if($this->cocuksayisi>0 && $this->esCalisiyorMu){
            switch($this->cocuksayisi){
                case 1:
                    $temp += ($this->brut_ucret /100) * 7.5;
                break;
                case 2:
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 7.5;
                break;
                case 3:
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 10;
                break;
                case 4:
                    $temp += ($this->brut_ucret /100) * 7.5; //birinci cocuk %7.5
                    $temp += ($this->brut_ucret /100) * 7.5; //ikinci cocuk %7.5
                    $temp += ($this->brut_ucret /100) * 10; //ucuncu cocuk %10
                    $temp += ($this->brut_ucret /100) * 5; ////dorduncu cocuk %5
                break;
            }
        }
        
        if($this->cocuksayisi>0 && !$this->esCalisiyorMu){
            switch($this->cocuksayisi){
                case 1:
                    $temp += ($this->brut_ucret /100) * 7.5;
                break;
                case 2:
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 7.5;
                break;
                case 3:
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 7.5;
                    $temp += ($this->brut_ucret /100) * 10;
                break;
            }
        }
        return $temp;
     }
 }
?>