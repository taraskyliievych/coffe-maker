<?php

const menu = [
    'italy' => [
        'portion' => 'single',
        'syrup' => 'coconut',
        'complement' => 'krouasan',
        'prices' => [
            'coffee' => 1.5,
            'milk' => 0.4,
            'syrup' => 0.5,
            'tax' => 0.07,
            'complement' => 1
        ]

    ],
    'spain' => [
        'portion' => 'double',
        'syrup' => 'melon',
        'complement' => '1 chocolate bar',
        'prices' => [
            'coffee' => 1,
            'milk' => 0.3,
            'syrup' => 0.5,
            'tax' => 0.03,
            'complement' => 1
        ]
    ]
];

/**
 * @param $session
 * @return string
 */
function getIngredientsText($session)
{
    $ingredientsText = 'It is ' . $session['country'] . ' coffee ';
    $ingredientsText .= 'with  ' . menu[$session['country']]['portion'] . ' portion of coffee,';

    if ($session['milk']) {
        $ingredientsText .= ' with milk,';
    } else {
        $ingredientsText .= ' without milk,';
    }

    if ($session['syrup']) {
        $ingredientsText .= ' with  ' . menu[$session['country']]['syrup'] . ' syrup,';
    } else {
        $ingredientsText .= ' without syrup,';
    }

    if ($session['complement']) {
        $ingredientsText .= ' with  ' . menu[$session['country']]['complement'] . ' complement,';
    } else {
        $ingredientsText .= 'without complement.';
    }

    return $ingredientsText;
}

/**
 * @param $session
 * @return float|int|mixed
 */
function getPrice($session)
{
    $price = menu[$session['country']]['prices']['coffee'];
    $price = $session['syrup'] == 1 ? $price + menu[$session['country']]['prices']['syrup'] : $price;
    $price = $session['milk'] == 1 ? $price + menu[$session['country']]['prices']['milk'] : $price;
    $price = $session['complement'] == 1 ? $price + menu[$session['country']]['prices']['complement'] : $price;
    $price = $price + ($price * menu[$session['country']]['prices']['tax']);

    return $price;
}

if (session_status() != 2) {
    session_start();
}

if (isset($_POST["reset"])) {
    session_unset();
    $mode = 1;
    $_SESSION["mode"] = 1;
}

if ($_SESSION["mode"] == 1) {
    if (isset($_POST["country"]) && !isset($_POST["reset"])) {
        $mode = 2;
        $_SESSION["mode"] = 2;
        $_SESSION["country"] = $_POST["country"];
    } else {
        $mode = 1;
    }
}

if ($_SESSION["mode"] == 2) {
    if (isset($_POST["syrup"])) {
        $mode = 3;// switch to next
        $_SESSION["mode"] = 3;
        $_SESSION["syrup"] = $_POST["syrup"];
    } else {
        $mode = 2;
    }
}

if ($_SESSION["mode"] == 3) {
    if (isset($_POST["complement"])) {
        $mode = 4;
        $_SESSION["mode"] = 4;
        $_SESSION["complement"] = $_POST["complement"];
    } else {
        $mode = 3;
    }
}

if ($_SESSION["mode"] == 4) {
    if (isset($_POST["milk"])) {
        $mode = 5;
        $_SESSION["mode"] = 5;
        $_SESSION["milk"] = $_POST["milk"];
    } else {
        $mode = 4;
    }
}

if ($_SESSION["mode"] == 5) {
    $mode = 5;
}

?>

<form action="" method="post">
    <?php if ($mode == 1) { ?>
        <label>country:
            <select name="country">
                <option value="spain">Spain</option>
                <option value="italy">Italy</option>
            </select>
        </label><br>
    <?php } ?>
    <?php if ($mode == 2) { ?>
        syrup:
        <input type="radio" id="yes" name="syrup" value="1">
        <label for="yes">Yes</label>
        <input type="radio" id="no" name="syrup" value="0">
        <label for="no">No</label><br>
    <?php } ?>
    <?php if ($mode == 3) { ?>
        complement:
        <input type="radio" id="yes" name="complement" value="1">
        <label for="yes">Yes</label>
        <input type="radio" id="no" name="complement" value="0">
        <label for="no">No</label><br>
    <?php } ?>
    <?php if ($mode == 4) { ?>
        milk:
        <input type="radio" id="yes" name="milk" value="1">
        <label for="yes">Yes</label>
        <input type="radio" id="no" name="milk" value="0">
        <label for="no">No</label><br>
    <?php } ?>
    <?php
    if ($mode == 5) {
        echo getIngredientsText($_SESSION);
        echo '</br>';
        echo 'price= ' . getPrice($_SESSION);
    }
    ?>
    <br><br>
    <?php if ($mode < 4) { ?><input type="submit" value="Next"> <?php } ?>
    <?php if ($mode == 4) { ?><input type="submit" value="Make coffee"> <?php } ?>
    <input name="reset" type="submit" value="Reset">
</form>
