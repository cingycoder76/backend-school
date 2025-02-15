<?php
include 'db.php';

// Prepare the base query
$query = "SELECT * FROM teachers";

// Check if a search term is provided and add filtering conditions
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = '%' . $_GET['search'] . '%'; // Add wildcard characters for LIKE query
    $query .= " WHERE name LIKE :searchTerm 
                OR email LIKE :searchTerm 
                OR phonenumber LIKE :searchTerm";
}

// Prepare the statement
$stmt = $pdo->prepare($query);

// Bind parameters if the search term is present
if (isset($searchTerm)) {
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
}

// Execute the query
$stmt->execute();

// Fetch the results
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are any teachers found
$hasTeachers = count($teachers) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-blue-600 mb-4">Teachers List</h1>

        <!-- Search Bar -->
        <div class="mb-4">
            <form method="GET" class="flex space-x-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by name, email, or phone number" 
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 transition duration-300">
                    Search
                </button>
            </form>
        </div>

        <!-- Teachers Table -->
        <?php if ($hasTeachers): ?>
            <table class="table-auto w-full bg-white shadow-md rounded-md border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Role</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $row): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2 text-center"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['role']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($row['phonenumber']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-700">No teachers found in the database.</p>
        <?php endif; ?>

        <!-- Footer -->
        <footer class="text-center mt-10 text-gray-500">
            &copy; 2024 Badimalika Secondary School. All rights reserved.
        </footer>
    </div>
</body>
</html>
