<?php
class FormHandler
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("127.0.0.1", "root", "", "tugas-form");
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function processForm()
    {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $whatsapp = $_POST['whatsapp'];
        $alamat = $_POST['alamat'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: tugas-form.php?error=invalidemail");
            exit;
        }

        $sql = "INSERT INTO form (nama, email, whatsapp, alamat) VALUES ('$nama', '$email', '$whatsapp', '$alamat')";
        if ($this->conn->query($sql) === TRUE) {
            header("Location: tugas-form.php?success=true");
            exit;
        } else {
            header("Location: tugas-form.php?error=databaseerror");
            exit;
        }
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

class DataViewer
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("127.0.0.1", "root", "", "tugas-form");
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function viewData()
    {
        $sql = "SELECT * FROM form";
        $result = $this->conn->query($sql);

        echo "<table border='1'>";
        echo "<tr><th>Nama</th><th>Email</th><th>WhatsApp</th><th>Alamat</th></tr>";
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["nama"] . "</td><td>" . $row["email"] . "</td><td>" . $row["whatsapp"] . "</td><td>" . $row["alamat"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        echo "</table>";
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$formHandler = new FormHandler();
$dataViewer = new DataViewer();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formHandler->processForm();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <style>
        body {
            background-image: url('https://pixexid.com/api/download/image/a-4k-ultra-hd-wallpaper-of-a-fox-dressed-in-a-computer-programmers-outfit-sitt-g9e6vlbb.jpeg');
            background-size: cover;
            background-position: 0, -50px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 800px;
            margin: 50px auto;
            padding: 22px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(249, 249, 249, 0.3);
            flex-direction: row-reverse;
        }

        .form-container,
        .data-container {
            width: 60%;
        }

        .form-container {
            margin-left: 40px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px 0px 10px 0;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1>Form Pendaftaran</h1>
            <div id="alert" style="display: none;"></div>
            <form method="POST" action="tugas-form.php">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="whatsapp">WhatsApp:</label>
                <input type="tel" id="whatsapp" name="whatsapp" required>

                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
        <div class="data-container">
            <h1>Data</h1>
            <?php $dataViewer->viewData(); ?>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            var alert = document.getElementById('alert');
            alert.style.display = 'block';
            alert.textContent = 'Data telah disubmit!';
            alert.style.backgroundColor = '#4CAF50';
            alert.style.color = '#fff';
            alert.style.padding = '10px';
            alert.style.marginBottom = '15px';

            setTimeout(function() {
                alert.style.display = 'none';
            }, 3000);
        });
    </script>
</body>

</html>