<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;

$currentDate = new DateTime();
$three_days_in_seconds = 3*24*60*60;

foreach($arResult['ITEMS'] as &$arItem){
    $arItem["IS_HOT"]=false;
    $arItem["IS_EXPIRED"]=false;
    $arItem["BADGE"] = '';

    /* РАЗМЕР СКИДКИ */
    $promo_item_discount = $arItem['DISPLAY_PROPERTIES']['DISCOUNT_PERCENT']['DISPLAY_VALUE'];
    if($promo_item_discount && $promo_item_discount !== 0 && $promo_item_discount !== ''){
        $arItem["DISCOUNT_PERCENT"] = $promo_item_discount . '%';
    }

    /* БЕЙДЖ СКИДКИ */
   if($promo_item_discount > 20){
        $arItem["BADGE"] = Loc::getMessage("SUPER_PRICE");
            }else{
        $arItem["BADGE"] = Loc::getMessage("BENEFIT_PRICE");
        };
        $promo_item_active_date = $arItem['DISPLAY_PROPERTIES']['DATE_ACTIVE_TO']['DISPLAY_VALUE'];
        
        /* АКТИВНА ЛИ АКЦИЯ */
        if(!empty($promo_item_active_date)){
            $expirationDate = DateTime::createFromUserTime($promo_item_active_date);
            if($expirationDate->getTimestamp() < $currentDate->getTimestamp() || $expirationDate->getTimestamp() < 0){
            $arItem["IS_EXPIRED"]=true;
        }elseif($three_days_in_seconds > ($expirationDate->getTimestamp() - $currentDate->getTimestamp())){
            /* ЗАКАНЧИВАЕТСЯ ЛИ АКЦИЯ */
        $arItem["IS_HOT"]=true;
            $arItem["HOT_BADGE"] = Loc::getMessage("HOT_PRICE");
        }
    }
}
unset($arItem);

