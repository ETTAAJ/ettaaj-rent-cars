<?php
require 'config.php';

// Function to validate same-day booking is not allowed
function validateBookingDates($pickupDate, $returnDate) {
  $errors = [];
  
  // Check if pickup date is today or in the past
  $today = new DateTime();
  $today->setTime(0, 0, 0);
  
  $pickup = new DateTime($pickupDate);
  $pickup->setTime(0, 0, 0);
  
  $return = new DateTime($returnDate);
  $return->setTime(0, 0, 0);
  
  // Same-day booking validation
  if ($pickup <= $today) {
    $errors[] = "Same-day booking is not allowed. Please choose a date starting from tomorrow.";
  }
  
  // Past date validation
  if ($pickup < $today) {
    $errors[] = "Pickup date cannot be in the past.";
  }
  
  // Return date must be after pickup date
  if ($return <= $pickup) {
    $errors[] = "Return date must be after pickup date.";
  }
  
  // Minimum rental period (3 days)
  $minDays = 3;
  $daysDiff = $pickup->diff($return)->days;
  if ($daysDiff < $minDays) {
    $errors[] = "Minimum rental period is {$minDays} days.";
  }
  
  return $errors;
}

if ($_POST) {
  $car_id = $_POST['car_id'] ?? 0;
  $pickup = $_POST['pickup'] ?? '';
  $return = $_POST['return'] ?? '';
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $phone = $_POST['phone'] ?? '';

  // Validate booking dates
  $validationErrors = validateBookingDates($pickup, $return);
  
  if (!empty($validationErrors)) {
    // Redirect back with error message
    $_SESSION['booking_error'] = implode(' ', $validationErrors);
    header("Location: booking.php?id=" . $car_id);
    exit;
  }

  // In real app: save to `bookings` table
  // For now: show confirmation
}
?>
<?php include 'header.php'; ?>

<div class="max-w-xl mx-auto text-center py-20">
  <h1 class="text-4xl font-bold text-gold mb-4">Booking Confirmed!</h1>
  <p class="text-lg text-gray-700">Thank you, <strong><?php echo htmlspecialchars($name); ?></strong>.</p>
  <p class="mt-4">We'll contact you at <strong><?php echo htmlspecialchars($email); ?></strong> soon.</p>
  <a href="index.php" class="inline-block mt-8 bg-gold hover:bg-gold-dark text-white font-bold py-3 px-8 rounded-full">
    Back to Home
  </a>
</div>

<?php include 'footer.php'; ?>
