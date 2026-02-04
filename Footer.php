<?php
/**
 * SafeHaven – footer.php
 * Shared page footer. Include at the bottom of every page.
 *
 * Usage:  require_once 'footer.php';
 *
 * Expects $extraJs array (optional) — paths to page-specific JS files.
 */
?>

<!-- ─── FOOTER ─────────────────────────────────── -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand column -->
            <div class="footer-col">
                <div class="footer-brand-logo">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7eb8da" stroke-width="2" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>SafeHaven</span>
                </div>
                <p>Empowering emergency evacuation through accessible communication and coordination across communities.</p>
            </div>

            <!-- Quick links -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#features">Features</a></li>
                    <li><a href="index.php#how-it-works">How It Works</a></li>
                    <li><a href="index.php#contact">Contact</a></li>
                </ul>
            </div>

            <!-- Contact info -->
            <div class="footer-col">
                <h4>Information</h4>
                <ul>
                    <li><a href="tel:+639477153075">+63 947 7153 075</a></li>
                    <li><a href="mailto:safehaven@support.com">safehaven@support.com</a></li>
                    <li><a href="#">www.safehaven.com</a></li>
                    <li>Cebu City, Philippines</li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider" />
        <p class="footer-bottom">&copy; <?= date('Y') ?> SafeHaven. All Rights Reserved.</p>
    </div>
</footer>

<!-- ─── GLOBAL JS ──────────────────────────────── -->
<script src="js/main.js"></script>

<!-- Page-specific scripts -->
<?php foreach (($extraJs ?? []) as $script): ?>
    <script src="<?= htmlspecialchars($script) ?>"></script>
<?php endforeach; ?>

</body>
</html>