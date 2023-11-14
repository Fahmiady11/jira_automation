<?php
require_once 'vendor/autoload.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form PHP</title>
</head>

<body>

    <?php

    // basic auth
    Unirest\Request::auth('asfahmi5@gmail.com', 'ATATT3xFfGF08aLPR6ONyI6BGKfkJxyO692KgLJtWA9NmJMQaKFOaIoBnmBcfHMKZwDeQp8aWuUxkMRfW18836-72r4sT5GwpzxPNEj7x_jE_hrAud-2WbvLd6gx8CUPu8Mz7wmW1pPoBWpyV4B3a4YGyn1UhVYqWsIYNSSlLkXPN3st0IzlWEQ=91B1F69D');

    $headers = array(
        'Accept' => 'application/json'
    );

    $response = Unirest\Request::get(
        'https://projectppl.atlassian.net/rest/api/3/users',
        $headers
    );

    $result = $response->raw_body;
    // Melakukan decode string JSON menjadi array PHP
    $data = json_decode($result, true);

    $dataUser = array();

    foreach ($data as $i => $value) {
        if ($value['accountType'] == 'atlassian') {
            $userData = array(
                'displayName' => $value['displayName'],
                'emailAddress' => $value['emailAddress'],
                'accountId' => $value['accountId'],
            );
            $dataUser[] = $userData;
        }
    }


    // Proses form saat tombol diklik
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mendapatkan nilai dari input
        $input_title = htmlspecialchars($_POST["title"]);
        $input_description = htmlspecialchars($_POST["description"]);
        $input_ticket = htmlspecialchars($_POST["ticket"]);
        $input_assign = htmlspecialchars($_POST["selected_option"]);
        $input_userRequest = htmlspecialchars($_POST["selected_option"]);

        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        );

        // basic auth
        Unirest\Request::auth('asfahmi5@gmail.com', 'ATATT3xFfGF08aLPR6ONyI6BGKfkJxyO692KgLJtWA9NmJMQaKFOaIoBnmBcfHMKZwDeQp8aWuUxkMRfW18836-72r4sT5GwpzxPNEj7x_jE_hrAud-2WbvLd6gx8CUPu8Mz7wmW1pPoBWpyV4B3a4YGyn1UhVYqWsIYNSSlLkXPN3st0IzlWEQ=91B1F69D');

        $body = <<<REQUESTBODY
        {
            "fields": {
                "project": {
                    "key": "TES"
                },
                "summary": "$input_title",
                "description": "$input_description",
                "issuetype": {
                    "name": "Task"
                },
                "customfield_10051": "$input_ticket",
                "customfield_10052": "$input_ticket",
                "customfield_10053": "$input_userRequest",
                "assignee": {"id": "$input_assign"}
            }
        }
        REQUESTBODY;

        $response = Unirest\Request::post(
            'https://projectppl.atlassian.net/rest/api/2/issue',
            $headers,
            $body
        );
    }
    ?>

    <!-- Formulir input PHP -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="Title">Title :</label>
            <input type="text" id="title" name="title" required>
        </div>
        <br>
        <div>
            <label for="Description">Description :</label>
            <input type="text" id="description" name="description" required>
        </div>
        <br>
        <div>
            <label for="input_text">Assign :</label>
            <select id="selected_option" name="selected_option" required>
                <?php
                // Mengisi elemen pilihan (option) dengan nilai dari array
                foreach ($dataUser as $option) {
                ?>
                    <option value="<?= $option['accountId'] ?>" $selected><?= $option['displayName'] ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <br>
        <div>
            <label for="input_text">User Request :</label>
            <select id="selected_option" name="selected_option" required>
                <?php
                // Mengisi elemen pilihan (option) dengan nilai dari array
                foreach ($dataUser as $option) {
                ?>
                    <option value="<?= $option['accountId'] ?>" $selected><?= $option['displayName'] ?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <br>
        <div>
            <label for="Ticket">Ticket :</label>
            <input type="text" id="ticket" name="ticket" required>
        </div>
        <br>
        <div>
            <label for="input_text">Labels :</label>
            <input type="text" id="input_text" name="input_text" required>
        </div>
        <br>
        <button type="submit">Kirim</button>
    </form>

</body>

</html>