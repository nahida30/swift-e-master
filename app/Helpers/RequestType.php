<?php

namespace App\Helpers;

class RequestType
{
  public static function get()
  {
    return [
      'AFF' => 'AFFIDAVIT',
      'AFF TX' => 'AFFIDAVIT OF LOST MORTGAGE (Mortgage Stamps & Intangible Tax)',
      'AGR' => 'AGREEMENT',
      'AGR TX' => 'AGREEMENT TAXABLE (Mortgage Stamps & Intangible Tax)',
      'AGD' => 'AGREEMENT FOR DEED (Deed & Mortgage Stamps & Intangible Tax)',
      'ASG' => 'ASSIGNMENT',
      'ASG TX' => 'ASSIGNMENT TAXABLE (Deed Stamps)',
      'CTF' => 'CERTIFICATE',
      'CP' => 'COURT PAPERS',
      'CP FGM' => 'COURT PAPERS FAMILY GUARDIANSHIP MENTAL HEALTH (Web Protected)',
      'CND' => 'DECLARATION OF CONDOMINIUM',
      'CND A' => 'DECLARATION OF CONDOMINIUM AMENDMENT',
      'DC' => 'DEATH CERTIFICATE (Web Protected)',
      'D' => 'DEED (Deed Stamps)',
      'D SMP' => 'DEED WITH ASSUMPTION (Deed & Mortgage Stamps)',
      'D TR' => 'TRUSTEES NON-JUDICIAL FORECLOSURE DEED (Deed Stamps & $50.00 DOR Admin fee)',
      'DM' => 'DEED/MORTGAGE (Deed & Mortgage Stamps & Intangible Tax)',
      'EAS' => 'EASEMENT (Deed Stamps)',
      'FIN' => 'FINANCING STATEMENT',
      'GOV' => 'GOVERNMENT RELATED',
      'IA' => 'INTERLOCAL AGREEMENT',
      'JUD FGM' => 'JUDGMENT FAMILY GUARDIANSHIP MENTAL HEALTH (Web Protected)',
      'JUD C' => 'JUDGMENT CERTIFIED',
      'LN' => 'LIEN',
      'LN TX' => 'TAX LIEN',
      'LN TX NC' => 'TAX LIEN NO CHARGE (Per CH 201)',
      'LN HSP' => 'HOSPITAL LIEN ($2.00 Flat Fee)',
      'LN HSP R' => 'HOSPITAL LIEN RELEASE ($2.00 Flat Fee)',
      'MAR' => 'MARRIAGE RECORD',
      'MOD' => 'MODIFICATION (Mortgage Stamps & Intangible Tax)',
      'MTG' => 'MORTGAGE (Mortgage Stamps & Intangible Tax)',
      'MTG DOC EX' => 'MORTGAGE DOCUMENTARY STAMP EXEMPT (Intangible Tax)',
      'MTG EXE' => 'MORTGAGE EXEMPT',
      'MTG INT EX' => 'MORTGAGE INTANGIBLE TAX EXEMPT (Mortgage Stamps)',
      'NT' => 'PROMISSORY NOTE (Mortgage Stamps)',
      'NT RP' => 'PROMISSORY NOTE SECURED BY REAL PROPERTY (Mortgage Stamps & Intangible Tax)',
      'NOT' => 'NOTICE',
      'NOC' => 'NOTICE OF COMMENCEMENT',
      'ORD' => 'ORDER',
      'ORD FGM' => 'ORDER FAMILY GUARDIANSHIP MENTAL HEALTH (Web Protected)',
      'PR' => 'PARTIAL RELEASE',
      'PLR' => 'PLAT RELATED',
      'POA' => 'POWER OF ATTORNEY',
      'PRO C' => 'PROBATE DOCUMENT CERTIFIED (Web Protected)',
      'REL' => 'RELEASE',
      'LN TX R' => 'RELEASE/SATISFACTION OF TAX LIEN',
      'LN TX R NC' => 'RELEASE/SATISFACTION OF TAX LIEN NO CHARGE (Per CH 201)',
      'RES' => 'RESTRICTIONS',
      'SAT' => 'SATISFACTION',
      'TER' => 'TERMINATION',
    ];
  }

  public static function prices()
  {
    return [
      'Gold Member' => 7.95,
      'Pay as you go' => 10.95,
    ];
  }
}
