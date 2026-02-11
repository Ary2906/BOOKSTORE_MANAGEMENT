<?php
$files_to_delete = [
    'verify_random_images.php',
    'verify_images_fixed.php',
    'verify_images.php',
    'verify_fix.php',
    'verify_complete.php',
    'verify_books.php',
    'verify_all_images.php',
    'update_complete.php',
    'test_images.php',
    'test_hf.php',
    'status.php',
    'smart_chatbot_openai.php',
    'smart_chatbot.php',
    'shop_new.php',
    'shop_backup.php',
    'setup_unique_images.php',
    'setup_stock.php',
    'setup_openai_chatbot.php',
    'setup_new_book_images.php',
    'setup_complete_final.php',
    'setup_complete.php',
    'setup_book_images.php',
    'setup_books.php',
    'safety_check.php',
    'reset_and_add_books.php',
    'reorganize_books.php',
    'remove_duplicates.php',
    'nuclear_fix.php',
    'master_fix.php',
    'make_images.php',
    'image_diagnostic.php',
    'home_clean.php',
    'guarantee_fix.php',
    'get_real_book_covers.php',
    'generate_images_fallback.php',
    'generate_images.php',
    'full_report.php',
    'free_chatbot_info.php',
    'fix_stock_complete.php',
    'fix_images_working.php',
    'fix_images_and_database.php',
    'fix_filenames.php',
    'fix_category_images.php',
    'fix_book_images.php',
    'fix_and_verify.php',
    'fix_all_images_real.php',
    'finish_setup.php',
    'find_missing_images.php',
    'find_duplicates.php',
    'final_verification.php',
    'final_stock_setup.php',
    'final_report.php',
    'final_proof.php',
    'final_image_fix.php',
    'final_fix_all.php',
    'finalize_books.php',
    'download_unique_book_images.php',
    'download_book_images.php',
    'download_book_covers.php',
    'diagnostic.php',
    'diagnose_images.php',
    'diagnose.php',
    'detailed_diagnosis.php',
    'description_guide.php',
    'deep_fix_images.php',
    'debug_stock.php',
    'create_unique_colors.php',
    'create_real_images.php',
    'create_png_images.php',
    'create_bulk_images.php',
    'create_book_images.php',
    'create_bmp_images.php',
    'complete_verification.php',
    'complete_fix.php',
    'check_status.php',
    'check_final_count.php',
    'check_database.php',
    'check_columns.php',
    'check_broken_images.php',
    'check_books_count.php',
    'check_books.php',
    'check_image_repetition.php',
    'cleanup_database.php',
    'chatbot_free_hf.php',
    'chatbot_enhanced.php',
    'chatbot_book_recommendations.php',
    'assign_unique_images.php',
    'assign_random_stock.php',
    'assign_random_images.php',
    'assign_book_images.php',
    'add_stock_column.php',
    'add_sample_books_auto.php',
    'add_sample_books.php',
    'add_random_images.php',
    'add_missing_images.php',
    'add_description_column.php',
    'add_category_images.php',
    'add_category_column.php',
    'add_book_descriptions.php',
    'add_books_direct.php',
    'add_25_new_books.php',
    'add_15_books_per_category.php',
    'add_125_books.php',
];

echo "<h2>🗑️ Cleanup Report</h2>";
echo "<style>
body { font-family: Arial; margin: 40px; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
th { background: #059669; color: white; }
</style>";

$deleted = 0;
$failed = 0;
$failed_files = [];

foreach($files_to_delete as $file) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
    
    if(file_exists($path)) {
        if(unlink($path)) {
            $deleted++;
        } else {
            $failed++;
            $failed_files[] = $file;
        }
    }
}

echo "<div class='success'>";
echo "<h3>✓ Cleanup Complete</h3>";
echo "<p><strong>" . $deleted . "</strong> temporary files removed</p>";
if($failed > 0) {
    echo "<p style='color: #dc2626;'><strong>" . $failed . "</strong> files could not be deleted:</p>";
    echo "<ul>";
    foreach($failed_files as $f) {
        echo "<li>" . $f . "</li>";
    }
    echo "</ul>";
}
echo "</div>";

// Count remaining PHP files
$remaining = count(glob('*.php'));
echo "<p><strong>Remaining PHP files:</strong> " . $remaining . "</p>";
?>
