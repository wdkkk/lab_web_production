    <?php
    require_once "TwigBaseController.php";

    class SearchController extends TwigBaseController
    {
        public $template = "./pages/search.twig";

        public function getContext(): array
        {
            $context = parent::getContext();

            $age = $_GET['age'] ?? '';
            $title = $_GET['title'] ?? '';
            $description = $_GET['description'] ?? '';

            $sql = <<<EOL
    SELECT title, short_name
    FROM cars_table
    WHERE (:title = '' OR title like CONCAT('%', :title, '%'))
    AND (:age = '' OR age = :age)
    EOL;

            $query = $this->pdo->prepare($sql);

            $query->bindValue("title", $title);
            $query->bindValue("age", $age);
            $query->bindValue("description", $description);
            $query->execute();

            $context['search_params'] = [
                "age" => $age,
                "title" => $title,
                "description" => $description
            ];

            $context['objects'] = $query->fetchAll();

            return $context;
        }
    }
