<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $data = $this->scrapFromHtml($dom);
    $this->writeToXlsx($data);

    return [];
  }

  /**
   * Função para pegar informações do site.
   */
  public function scrapFromHtml(\DOMDocument $dom): array {

    $xpath = new \DOMXPath($dom);
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
    return $data;
  }

  /**
   * Função para escrever o conteúdo no arquivo.
   */
  public function writeToXlsx($data): array {

    $filePath = 'assets\model.xlsx';
    print_r("teste");

    $writer = WriterEntityFactory::createWriterFromFile($filePath);

    $writer->openToFile($filePath);

    $headerRow = WriterEntityFactory::createRowFromArray(
      [
      'ID',
      'Title',
      'Type',
      'Author 1',
      'Author 1 Institution',

      'Author 2',
      'Author 2 Institution',
      'Author 3',
      'Author 3 Institution',
      'Author 4',
      'Author 4 Institution',
      'Author 5',
      'Author 5 Institution',
      'Author 6',
      'Author 6 Institution',
      'Author 7',
      'Author 7 Institution',
      'Author 8',
      'Author 8 Institution',
      'Author 9',
      'Author 9 Institution']
    );  

    $writer->addRow($headerRow);

    foreach ($data as $rowData) {
      $rowArray = (
        [
        $rowData->id,
        $rowData->title,
        $rowData->type
        ]
    );

      foreach ($rowData->authors as $author) {
        $rowArray[] = $author->name;
        $rowArray[] = $author->institution;
      }

      $row = WriterEntityFactory::createRowFromArray($rowArray);

      $style = (new StyleBuilder())->setShouldWrapText(FALSE)->build();
      foreach ($rowArray as $index => $value) {
        $row->getCellAtIndex($index)->setStyle($style);
      }

      $writer->addRow($row);
    }

    $writer->close();

    return [];
  }

}
