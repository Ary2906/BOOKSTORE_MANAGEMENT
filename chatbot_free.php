<?php

// Smart Chatbot Backend - No API Key Required
// Intelligent book recommendations using database

require 'config.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['message'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Message is required']);
        exit;
    }
    
    $user_message = trim($input['message']);
    
    if (empty($user_message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Message cannot be empty']);
        exit;
    }
    
    // Get smart chatbot response with database lookup
    $response_data = get_smart_response($user_message);
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => $response_data['message'],
        'books' => $response_data['books'] ?? [],
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}

/**
 * Get smart response with book recommendations from database
 */
function get_smart_response($user_message) {
    global $conn;
    
    $message_lower = strtolower($user_message);
    
    // 1. FIRST - Try to find SPECIFIC BOOK TITLES (exact match or partial)
    $specific_books = find_specific_books($user_message);
    if (!empty($specific_books)) {
        $response = "📚 Perfect! I found the book(s) you're looking for:\n\n";
        
        foreach ($specific_books as $book) {
            $stock_status = $book['stock'] > 0 ? "✅ In Stock (" . $book['stock'] . " available)" : "❌ Out of Stock";
            $response .= "**" . $book['name'] . "**\n";
            $response .= "Category: " . $book['category'] . "\n";
            $response .= "Price: Rs. " . $book['price'] . "/-\n";
            $response .= "Stock: " . $stock_status . "\n";
            if (!empty($book['description'])) {
                $response .= "Description: " . substr($book['description'], 0, 100) . "...\n";
            }
            $response .= "\n";
        }
        
        return [
            'message' => $response . "Would you like to add any of these to your cart? 🛒",
            'books' => array_slice($specific_books, 0, 5)
        ];
    }
    
    // 2. Check for book recommendations based on genre/type
    $recommended_books = find_books_by_description($user_message);
    if (!empty($recommended_books)) {
        $response = "Great choice! 📚 I found some books you might love:\n\n";
        
        foreach ($recommended_books as $key => $book) {
            if ($key < 5) {
                $stock_status = $book['stock'] > 0 ? "✅ In Stock" : "❌ Out of Stock";
                $response .= "• **" . $book['name'] . "** - Rs. " . $book['price'] . "/- | " . $stock_status . "\n";
            }
        }
        
        return [
            'message' => $response . "\nWould you like to know more about any of these books? 😊",
            'books' => array_slice($recommended_books, 0, 5)
        ];
    }
    
    // 2. Handle greeting
    if (preg_match('/hello|hi|hey|greetings|good (morning|afternoon|evening)/i', $user_message)) {
        return [
            'message' => 'Hey there! 👋 Welcome to our bookstore! I can help you find the perfect book. Tell me about the type of book you\'re interested in, or ask about any title! 📖'
        ];
    }
    
    // 3. Handle search for specific books
    if (preg_match('/search|find|look for|what.*have|recommend|suggest/i', $user_message)) {
        return [
            'message' => 'I\'d love to help you find a book! 🔍 Tell me:\n• What genre or type of book are you interested in?\n• Or describe what you\'re looking for?\n\nExamples: mystery thriller, science fiction, romance, educational, history, self-help, etc. 📚'
        ];
    }
    
    // 4. Handle stock/availability questions
    if (preg_match('/stock|available|in stock|out of stock|have.*copy/i', $user_message)) {
        return [
            'message' => 'I can check stock for you! 📦 What book are you interested in? Tell me the title or describe what you\'re looking for, and I\'ll let you know if it\'s in stock!'
        ];
    }
    
    // 5. Handle pricing and cart
    if (preg_match('/price|cost|how much|expensive|cheap|cart|buy|purchase|add to cart/i', $user_message)) {
        return [
            'message' => 'Our prices are competitive! 💰 Tell me which book you\'re interested in, and I\'ll show you the price and stock status. Then you can add it to your cart! 🛒'
        ];
    }
    
    // 6. Handle general questions
    if (preg_match('/contact|help|support|question|how can|what can|shipping|payment|order/i', $user_message)) {
        return [
            'message' => 'Great question! 💬 For specific details about shipping, payments, returns, or orders, please visit our Contact page or check out our FAQ. Is there a specific book I can help you find instead? 📚'
        ];
    }
    
    // 7. Default - ask about book preferences
    return [
        'message' => 'That sounds interesting! 🤔 I\'m here to help you find the perfect book! 📖\n\nCan you tell me:\n• What type of book are you looking for?\n• Any specific genre or topic?\n• Or a book title you\'re interested in?\n\nI\'ll search our catalog and let you know if we have it and if it\'s in stock! 😊'
    ];
}

/**
 * Find SPECIFIC BOOKS by title - high priority search
 */
function find_specific_books($user_query) {
    global $conn;
    
    // Extract potential book titles (words capitalized or longer phrases)
    $query_lower = strtolower($user_query);
    
    // Remove common question words
    $query_clean = preg_replace('/\b(do|you|have|show|me|a|an|the|is|are|can|any|all|where|when|what|recommend|suggest|tell|give|find|look|search|for)\b/i', '', $user_query);
    $query_clean = trim(preg_replace('/\s+/', ' ', $query_clean));
    
    // If nothing left after removing common words, return empty
    if (empty($query_clean) || strlen($query_clean) < 2) {
        return [];
    }
    
    // Search for exact or partial title matches
    $safe_query = mysqli_real_escape_string($conn, $query_clean);
    
    // Exact or partial match in book names
    $sql = "SELECT id, name, description, price, category, stock, image 
            FROM `products` 
            WHERE name LIKE '%$safe_query%'
            ORDER BY name ASC
            LIMIT 10";
    
    $result = mysqli_query($conn, $sql);
    $books = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($book = mysqli_fetch_assoc($result)) {
            $books[] = [
                'id' => $book['id'],
                'name' => htmlspecialchars($book['name']),
                'category' => htmlspecialchars($book['category']),
                'price' => $book['price'],
                'description' => htmlspecialchars(substr($book['description'], 0, 150)),
                'stock' => intval($book['stock']),
                'image' => $book['image']
            ];
        }
    }
    
    return $books;
}

/**
 * Find books by user description/genre - searches database intelligently
 */
function find_books_by_description($user_query) {
    global $conn;
    
    $keywords = extract_keywords($user_query);
    
    if (empty($keywords)) {
        return [];
    }
    
    // Build search query
    $where_conditions = [];
    foreach ($keywords as $keyword) {
        $safe_keyword = mysqli_real_escape_string($conn, $keyword);
        $where_conditions[] = "(name LIKE '%$safe_keyword%' OR description LIKE '%$safe_keyword%' OR category LIKE '%$safe_keyword%')";
    }
    
    $where_clause = implode(' OR ', $where_conditions);
    
    $query = "SELECT id, name, description, price, category, stock, image 
              FROM `products` 
              WHERE $where_clause
              ORDER BY stock DESC, price ASC
              LIMIT 10";
    
    $result = mysqli_query($conn, $query);
    $books = [];
    
    while ($book = mysqli_fetch_assoc($result)) {
        $books[] = [
            'id' => $book['id'],
            'name' => htmlspecialchars($book['name']),
            'category' => htmlspecialchars($book['category']),
            'price' => $book['price'],
            'description' => htmlspecialchars(substr($book['description'], 0, 100)) . '...',
            'stock' => $book['stock'],
            'image' => $book['image']
        ];
    }
    
    return $books;
}

/**
 * Extract meaningful keywords from user input
 */
function extract_keywords($text) {
    $text = strtolower($text);
    
    // Predefined genre and book-related keywords
    $book_keywords = [
        'mystery' => 'mystery',
        'thriller' => 'thriller',
        'romance' => 'romance',
        'fiction' => 'fiction',
        'science fiction' => 'science fiction',
        'sci-fi' => 'science fiction',
        'fantasy' => 'fantasy',
        'horror' => 'horror',
        'history' => 'history',
        'historical' => 'history',
        'biography' => 'biography',
        'educational' => 'educational',
        'self-help' => 'self-help',
        'personal development' => 'self-help',
        'business' => 'business',
        'technology' => 'technology',
        'programming' => 'programming',
        'poetry' => 'poetry',
        'adventure' => 'adventure',
        'action' => 'action',
        'drama' => 'drama',
        'comedy' => 'comedy',
        'children' => 'children',
        'kids' => 'children',
        'young adult' => 'young adult',
        'teen' => 'young adult'
    ];
    
    $found_keywords = [];
    
    // Look for matching keywords
    foreach ($book_keywords as $keyword => $category) {
        if (strpos($text, $keyword) !== false) {
            $found_keywords[] = $category;
        }
    }
    
    // Remove duplicates
    $found_keywords = array_unique($found_keywords);
    
    // If no keywords found, extract words from user input
    if (empty($found_keywords)) {
        $words = preg_split('/[\s,\.\?\!]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        // Take meaningful words (longer than 3 characters, excluding common words)
        $stop_words = ['the', 'and', 'for', 'with', 'that', 'this', 'from', 'about', 'like', 'want', 'need', 'tell', 'give', 'have', 'show', 'can', 'you', 'recommend', 'suggest', 'please', 'find', 'look', 'search'];
        
        foreach ($words as $word) {
            if (strlen($word) > 3 && !in_array($word, $stop_words)) {
                $found_keywords[] = $word;
            }
        }
    }
    
    return array_unique(array_slice($found_keywords, 0, 3));
}

?>
