<?php
require 'config.php';

// Function to validate same-day booking is not allowed (TIMEZONE-SAFE)
// Uses DATE ONLY (YYYY-MM-DD) to ensure identical calculation on all devices
function validateBookingDates($pickupDate, $returnDate) {
  $errors = [];
  
  // Validate date format (must be YYYY-MM-DD)
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $pickupDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $returnDate)) {
    $errors[] = "Invalid date format. Please select dates again.";
    return $errors;
  }
  
  // Create DateTime objects normalized to midnight (00:00:00) - DATE ONLY
  $today = new DateTime('today'); // Today at 00:00:00
  $pickup = DateTime::createFromFormat('Y-m-d', $pickupDate);
  $pickup->setTime(0, 0, 0);
  
  $return = DateTime::createFromFormat('Y-m-d', $returnDate);
  $return->setTime(0, 0, 0);
  
  // Same-day booking validation (pickup must be after today)
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
  
  // Calculate paid days: (endDate - startDate) in days + 1
  // This matches the JavaScript calculation exactly
  $minDays = 3;
  $interval = $pickup->diff($return);
  $paidDays = $interval->days + 1; // +1 because both start and end dates are included
  
  if ($paidDays < $minDays) {
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
