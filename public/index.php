<?php
session_start();
require_once '../config/database.php';

$page_title = 'TaxSafar - Professional CA Consultancy';
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    $errors = [];

    if (empty($full_name)) {
        $errors[] = 'Full Name is required';
    } elseif (strlen($full_name) < 3) {
        $errors[] = 'Full Name must be at least 3 characters';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($mobile)) {
        $errors[] = 'Mobile number is required';
    } elseif (!preg_match('/^[0-9]{10}$/', preg_replace('/[^\d]/', '', $mobile))) {
        $errors[] = 'Mobile number must be 10 digits';
    }

    if (empty($city)) {
        $errors[] = 'City is required';
    }

    if (empty($service)) {
        $errors[] = 'Service is required';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    } elseif (strlen($message) < 10) {
        $errors[] = 'Message must be at least 10 characters';
    }

    // If no validation errors, insert into database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('
                INSERT INTO inquiries (full_name, email, mobile, city, service, message, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $full_name,
                $email,
                $mobile,
                $city,
                $service,
                $message,
                'new'
            ]);

            $success_message = 'Thank you! Your inquiry has been submitted successfully. We will contact you shortly.';

            // Clear form
            $full_name = $email = $mobile = $city = $service = $message = '';
        } catch (Exception $e) {
            $error_message = 'Error submitting form. Please try again later.';
        }
    } else {
        $error_message = implode(' ', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-wrap">
            <a href="#home" class="navbar-brand" aria-label="TaxSafar home">
                <h1>TaxSafar</h1>
                <p>Tax and CA Consultancy</p>
            </a>
            <div class="navbar-links">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#inquiry">Contact</a>
                <a href="../admin/login.php" class="admin-link">Admin</a>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <img class="hero-image" src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1800&q=80" alt="Tax consultant reviewing financial documents">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <p class="eyebrow">Trusted CA support for individuals and businesses</p>
                <h2>Clear tax help, without the paperwork stress.</h2>
                <p>File returns, register your business, manage GST, and stay compliant with practical guidance from TaxSafar.</p>
                <div class="hero-actions">
                    <a href="#inquiry" class="cta-btn">Book a Consultation</a>
                    <a href="#services" class="secondary-btn">View Services</a>
                </div>
                <div class="hero-stats" aria-label="TaxSafar highlights">
                    <div>
                        <strong>8+</strong>
                        <span>Core services</span>
                    </div>
                    <div>
                        <strong>24h</strong>
                        <span>Response time</span>
                    </div>
                    <div>
                        <strong>&#8377;199</strong>
                        <span>ITR support from</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow">Services</p>
                <h2>Everything your tax file needs, in one place.</h2>
                <p>Choose the support you need today and leave the compliance details to us.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <span class="service-tag">ITR</span>
                    <h3>Income Tax Return Filing</h3>
                    <p>Online filing with expert CA support starting at just &#8377;199.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">GST</span>
                    <h3>GST Registration & Filing</h3>
                    <p>Quick GST number registration and monthly or quarterly return filing.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">PAN</span>
                    <h3>PAN Card Services</h3>
                    <p>New PAN application, PAN correction, and e-PAN download support.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">DSC</span>
                    <h3>Digital Signature Certificates</h3>
                    <p>Class 2 and Class 3 DSCs for individuals and organizations.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">MCA</span>
                    <h3>Company Registration</h3>
                    <p>Start your private limited, LLP, or partnership firm with legal support.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">MSME</span>
                    <h3>MSME / Udyam Registration</h3>
                    <p>Get your business recognized and avail government benefits.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">TDS</span>
                    <h3>TDS & ROC Filing</h3>
                    <p>End-to-end support for TDS compliance and MCA ROC returns.</p>
                </div>
                <div class="service-card">
                    <span class="service-tag">PLAN</span>
                    <h3>Financial & Investment Advisory</h3>
                    <p>Personalized consultation for tax saving, investments, and insurance.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="inquiry" class="inquiry-section">
        <div class="container">
            <div class="form-container">
                <div class="form-intro">
                    <p class="eyebrow">Contact</p>
                    <h2>Tell us what you need help with.</h2>
                    <p>Share your details and the TaxSafar team will get back with the next steps.</p>
                    <ul class="contact-points">
                        <li>Personal and business tax support</li>
                        <li>GST, TDS, ROC, PAN, DSC and MSME help</li>
                        <li>Clear guidance before you pay</li>
                    </ul>
                </div>
                <div class="form-panel">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="inquiry-form">
                        <div class="form-group">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name"
                                   value="<?php echo htmlspecialchars($full_name ?? ''); ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email"
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile Number *</label>
                                <input type="tel" id="mobile" name="mobile"
                                       value="<?php echo htmlspecialchars($mobile ?? ''); ?>"
                                       placeholder="10-digit number" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city"
                                       value="<?php echo htmlspecialchars($city ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="service">Service *</label>
                                <select id="service" name="service" required>
                                    <option value="">Select Service</option>
                                    <option value="Income Tax Return Filing" <?php echo ($service === 'Income Tax Return Filing') ? 'selected' : ''; ?>>Income Tax Return Filing</option>
                                    <option value="GST Registration & Filing" <?php echo ($service === 'GST Registration & Filing') ? 'selected' : ''; ?>>GST Registration & Filing</option>
                                    <option value="PAN Card Services" <?php echo ($service === 'PAN Card Services') ? 'selected' : ''; ?>>PAN Card Services</option>
                                    <option value="Digital Signature Certificates" <?php echo ($service === 'Digital Signature Certificates') ? 'selected' : ''; ?>>Digital Signature Certificates</option>
                                    <option value="Company Registration" <?php echo ($service === 'Company Registration') ? 'selected' : ''; ?>>Company Registration</option>
                                    <option value="MSME / Udyam Registration" <?php echo ($service === 'MSME / Udyam Registration') ? 'selected' : ''; ?>>MSME / Udyam Registration</option>
                                    <option value="TDS & ROC Filing" <?php echo ($service === 'TDS & ROC Filing') ? 'selected' : ''; ?>>TDS & ROC Filing</option>
                                    <option value="Financial & Investment Advisory" <?php echo ($service === 'Financial & Investment Advisory') ? 'selected' : ''; ?>>Financial & Investment Advisory</option>
                                    <option value="Other" <?php echo ($service === 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="submit-btn">Submit Inquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 TaxSafar - Professional CA Consultancy. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
