<?php
function displayPageTitle($result)
//Creates and displays collection and containers to display as a title and subtitle
{
    $firstAncestors = $result->results[0]->ancestors;
    $collection = "";
    $container = "";
    foreach ($firstAncestors as $ancestor) {
        $ancType = $ancestor->type;
        switch ($ancType) {
            case 'collection':
                $collection = $ancestor->name;
                break;
            case 'container':
                ($container === "") ? $container = $ancestor->name : $container = $container . " - " . $ancestor->name;
                break;
        }
    }
    $pageTitle = "<h1>" . $collection . "</h1> <h4>" . $container . "</h4>";
    echo $pageTitle;
    return;
}

function displayItems($result)
{
    foreach ($result->results as $result) {
        foreach ($result->attribute_values as $av) {
            if (empty($av->value)) continue;
            $avname = $av->attribute_name;
            $val = $av->value;
            switch ($avname) {
                case 'Description':
                    $description = $val->text;
                    break;
                case "Creation Date":
                    $date = $val->date;
                    break;
            }
        }
        if (!empty($result->digital_assets[0]->large_thumbnail)) {
            $image = $result->digital_assets[0]->large_thumbnail;
        }
        echo  '
        <div class="row project-item">
            <a href="articleview.html">
                <div class="col-sm-3">
                    <img src="' . $image . '" alt="" />
                </div>
                <div class="col-sm-3 col">
                    <span>Title</span>
                    ' . $result->name . '
                </div>
                <div class="col-sm-3 col">
                    <span>Year</span>
                    ' . strstr($date, "-", true) . '
                </div>
                <div class="col-sm-3">
                    ' . $description . '
                </div>
            </a>
        </div> 
        ';
    }
    return;
}

function pagination($result, $slicecount)
{
    $numOfPages = round($result->total_count / $slicecount);
    isset($_GET['page']) ? $currentPage = $_GET['page'] : $currentPage = 1;
    $prevPage = $currentPage - 1;
    $nextPage = $currentPage + 1;
    if ($prevPage > 0) {
        echo "<a href='?page=$prevPage'><i class='glyphicon glyphicon-triangle-left'></i></a>";
    }
    for ($i = 1; $i <= $numOfPages; $i++) {
        if ($i == $currentPage) {
            echo "<a class='selected' href='?page=$i'>$i</a>";
        } else {
            echo "<a href='?page=$i'>$i</a>";
        }
    }
    if ($nextPage < $numOfPages + 1) {
        echo "<a href='?page=$nextPage'><i class='glyphicon glyphicon-triangle-right'></i></a>";
    }
}
