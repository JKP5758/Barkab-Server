<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .form-container .form-group {
            margin-bottom: 15px;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container input[type="directory"],
        .form-container input[type="db"] {
            width: 90%;
            padding: 10px 15px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container input:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .form-container .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-container button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-container .save-btn {
            background-color: #28a745;
            color: white;
        }

        .form-container .save-btn:hover {
            background-color: #218838;
        }

        .form-container .cancel-btn {
            background-color: #dc3545;
            color: white;
        }

        .form-container .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nis">NIS:</label>
                <input type="text" id="nis" name="nis" placeholder="Masukkan NIS" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama pengguna" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password">
            </div>
            <div class="form-group">
                <label for="directory">Directory:</label>
                <input type="text" id="directory" name="directory" placeholder="Masukkan directory">
            </div>
            <div class="form-group">
                <label for="db">Database:</label>
                <input type="text" id="db" name="db" placeholder="Masukkan nama database">
            </div>
            <div class="form-actions">
                <button type="submit" class="save-btn">Simpan</button>
                <button type="button" class="cancel-btn" onclick="window.history.back();">Batal</button>
            </div>
        </form>
    </div>
</body>
</html>
