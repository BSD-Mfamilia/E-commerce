<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Grabo*estilo</title>
        <link rel="shortcut icon" type="image/png" href="img/graboestilo.ico" />
        <link rel="stylesheet" type="text/css" href="product_style.css" />
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">E-commerce</a>
            <form class="form-inline" role="Buscar" method="get" action="index.php">
                <input class="form-control mr-sm-2" type="search" name="product" value="<?= @$_GET['product']; ?>" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" value="Submit" type="submit">Search</button>
            </form>
        </nav>
    </body>
</html>

<!-- intro -->

<?php
	echo "</br>";
	echo "<div class='container'>";

	if (isset($_GET['page'])) {
		$startPage = $_GET['page'];
	} else {
		$startPage = null;
	}

	$perPage = 21;

	$pricefile = simplexml_load_file("http://localhost/XML/pricefile_$8013001.xml") or die("Error: Cannot create object");
	$ItemDataFile = simplexml_load_file("http://localhost/XML/alldatafile_esp.xml")or die("Error: Cannot create object");
	$allstockfile = simplexml_load_file("http://localhost/XML/allstockfile50.xml")or die("Error: Cannot create object");

	if (isset($_GET['product'])) {
		$search = strtolower($_GET['product']);
		if ($products = $ItemDataFile->xpath('/catalog/product/ref[.="'.$search.'"]/parent::*')){  
			$stocks = $allstockfile->xpath('/catalog/product/ref[.="'.$search.'"]/parent::*');
			$price = $pricefile->xpath('/catalog/product/ref[.="'.$search.'"]/parent::*');
				
			if(count($products) > 0) { 
				echo "<div class='row'>";
				foreach ($products as $products){
					if(count($stocks) > 0) {
						if(count($price) > 0) {
							echo "<div class='col-12 col-md-4'>";
							echo "<div class='products'>";
							echo "<img src=".$products[0]->variants->variant->image500px." class='img-fluid img-thumbnail'>";
							echo "<div class=caption> <h2>Name: ".$products[0]->name." </h2></div>";
							echo "<div class=caption> <h2>Type: ".$products[0]->type." </h2></div>";
							echo "<div class=caption> <h2>Stock: ".$stocks[0]->stock." </h2></div>";
							echo "<div class=caption> <h2>Price: ".$price[0]->price1." </h2></div>";
							echo "</div>";
							echo "</br>";
							echo "</div>";
						}
					}
				}
				echo "</div>";
			} 
		}

		if ($products = $ItemDataFile->xpath('/catalog/product/*[contains(translate(text(),"ABCDEFGHJIKLMNOPQRSTUVWXYZ","abcdefghjiklmnopqrstuvwxyz"),"'.$search.'")]/parent::*')){
			if(count($products) > 0) { 
				echo "<div class='row'>";
				foreach ($products as $products){
					$stocks = $allstockfile->xpath('/catalog/product/ref[.="'.$products[0]->ref.'"]/parent::*');
					$price = $pricefile->xpath('/catalog/product/ref[.="'.$products[0]->ref.'"]/parent::*');
					if(count($stocks) > 0) {
						if(count($price) > 0) {
							echo "<div class='col-12 col-md-4'>";
							echo "<div class='products'>";
							echo "<img src=".$products[0]->variants->variant->image500px." class='img-fluid img-thumbnail'>";
							echo "<div class=caption> <h2>Type: ".$products[0]->type." </h2></div>";
							echo "<div class=caption> <h2>Name: ".$products[0]->name." </h2></div>";
							echo "<div class=caption> <h2>Stock: ".$stocks[0]->stock." </h2></div>";
							echo "<div class=caption> <h2>Price: ".$price[0]->price1." </h2></div>";
							echo "</div>";
							echo "</br>";
							echo "</div>";
						}
					}
				}
				echo "</div>";
			}
		}
	} else {
		$pagination = new LimitPagination($startPage, $ItemDataFile->count(), $perPage);

		echo "<div class='row'>";
		foreach($pagination->getLimitIterator($ItemDataFile->product) as $product){

			/*
			* Obtener stock de un item especifico
			*
			*/
    
			$nodes = $allstockfile->xpath('/catalog/product/ref[.="'.$product->ref.'"]/parent::*');

			if (isset($nodes[0])) {
				$result = $nodes[0];
				$nodes1 = $pricefile->xpath('/catalog/product/ref[.="'.$product->ref.'"]/parent::*');
				$resultPrice = $nodes1[0];
    
				echo "<div class='col-12 col-md-4'>";
				echo "<div class='products'>";
				echo "<img src=".$product->variants->variant->image500px." class='img-fluid img-thumbnail'>";
				echo "<div class=caption> <h2>Type: $product->type </h2></div>";
				echo "<div class=caption> <h2>Name: $product->name </h2></div>";
				echo "<div class=caption> <h2>Stock: $result->stock </h2></div>";
				echo "<div class=caption> <h2>Price: $resultPrice->price1 </h2></div>";
				echo "</div>";
				echo "</br>";
				echo "</div>";
			}
		}
		echo "</div>";

		echo "<div class='container'>";
		echo "<ul class=' pagination pagination-sm'>";

		if (!$pagination->isFirstPage()) {
			printf("<li class='page-item'><a href=\"?page=%d\" class='page-link'>&lt;</a></li>", $pagination->getPreviousPage());
		}

		foreach ($pagination->getPageRange() as $page) {
			if ($page === $pagination->getPage()) {
				// current page
				printf("<li class='page-item disabled'><a href='#' class='page-link'>"."$page"."</a></li>");
			} else {
				printf("<li class='page-item'><a href=\"?page=%1\$d\" class='page-link'>%d </a></li>", $page);
			}
		}

		if (!$pagination->isLastPage()) {
			printf("<li class='page-item'><a href=\"?page=%d\" class='page-link'>&gt;</a></li>", $pagination->getNextPage());
		}

		echo "</ul>";
		echo "</div>";

	}

	echo "</div>";
   
	class LimitPagination
	{
		private $perPage, $page, $totalCount;
	
		/**
		 * @param int $page
		 * @param int $totalCount
		 * @param int $perPage
		 */

		public function __construct($page, $totalCount, $perPage = 10) {
	
			$this->setPerPage($perPage);
			$this->setTotalCount($totalCount);
			$this->setPage($page);
		}

		public function getPerPage() {
			return $this->perPage;
		}
	
		/**
		 * @param int $perPage
		 */

		public function setPerPage($perPage) {
			$this->perPage = max(1, (int) $perPage);
		}

		/**
		 * @return int
		 */

		public function getTotalCount() {
			return $this->totalCount;
		}
	
		/**
		 * @param int $totalCount
		 */

		public function setTotalCount($totalCount) {
			$this->totalCount = max(0, (int) $totalCount);
		}
	
		/**
		 * @return int
		 */

		public function getPage() {
			return min($this->getTotalPages(), $this->page);
		}
	
		/**
		 * @param int $page
		 */

		public function setPage($page) {
			$this->page = min($this->getTotalPages(), max(1, (int) $page));
		}
	
		/**
		 * @return int
		 */

		public function getNextPage() {
			return min($this->getTotalPages(), $this->page + 1);
		}
	
		/**
		 * @return int
		 */

		public function getPreviousPage() {
			return max(1, $this->page - 1);
		}
	
		/**
		 * @return int
		 */

		public function getTotalPages() {
			return (int) ceil($this->totalCount / $this->perPage);
		}
	
		/**
		 * @return bool
		 */

		public function isFirstPage() {
			return $this->page === 1;
		}
	
		/**
		 * @return bool
		 */

		public function isLastPage() {
			return $this->page === $this->getTotalPages();
		}
	
		/**
		 * @return int
		 */

		public function getOffset() {
			return ($this->getPage() - 1) * $this->perPage;
		}
	
		/**
		 * @return int
		 */

		public function getCount() {
			return $this->perPage;
		}
	
		/**
		 * @return string
		 */

		public function getSqlLimit() {
			return sprintf("LIMIT %d, %d", $this->getOffset(), $this->getCount());
		}
			
		/**
		 * @return LimitIterator
		 */

		public function getLimitIterator(Traversable $it) {
			if (!$it instanceof Iterator) {
				$it = new IteratorIterator($it);
			}
			return new LimitIterator($it, $this->getOffset(), $this->getCount());
		}
			
		/**
		 * @return array
		 */    

		public function getPageRange() {
			return $this->getTotalPages() ? range(1, $this->getTotalPages()) : array();
		}
	
		public function __toString() {
			return $this->getSqlLimit();
		}
	}

?>