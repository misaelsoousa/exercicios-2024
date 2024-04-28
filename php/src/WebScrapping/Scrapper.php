<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $data = $this->scrapFromHTML($dom);

    $this->writeToXLSX($data);    

    return [];
  }

  public function scrapFromHTML(\DOMDocument $dom): array {
    // Cria um novo objeto DOMXPath
    $xpath = new \DOMXPath($dom);
    // Seleciona todos os links dentro da terceira seção com a classe especificada
    $links = $xpath->query('//a[contains(@class, "paper-card")]');

    $data = [];

    foreach ($links as $link) {
      $id = $xpath->query('.//div[contains(@class, "volume-info")]', $link);
      $title = $xpath->query('.//h4[contains(@class, "paper-title")]', $link);
      $type = $xpath->query('.//div[contains(@class, "tags mr-sm")]', $link);

      $authors_container = $xpath->query('.//div[contains(@class, "authors")]', $link)[0];
      $authors = $xpath->query('.//span', $authors_container);

      $authors_array = [];

      foreach ($authors as $author) {

        $name = $author->textContent;
    
        $institution = $author->getAttribute("title");
        
        $person = new Person($name, $institution);
        array_push($authors_array, $person);
    }

      $paper = new Paper(
        $id[0]->textContent,
        $title[0]->textContent,
        $type[0]->textContent,
        $authors_array
      );
     array_push($data, $paper);
    }

    print_r($data);

    // new Paper(0, "", "", $authors_array);

    // Inicializa o array para armazenar os resultados
    $result = [];

    // Itera sobre os links encontrados
    // foreach ($links as $link) {
    //     // Extrai o título do elemento <h4> dentro do link atual
    //     $title = $link->getElementsByTagName("h4")->item(0)->textContent;
        
    //     // Adiciona o título ao resultado
    //     $result[] = $title;
    // }

    // Retorna o array de resultados
    return $result;
}


  public function writeToXLSX($data): array {
    
    return [];
  }
}
