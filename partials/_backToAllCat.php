<?php
echo '<style>
    .category-link {
        color: #333; 
        text-decoration: none; 
        font-size: 16px; 
        padding: 12px 20px; 
        border: none; 
        cursor: pointer;
        display: inline-flex; 
        align-items: center; 
        gap: 10px; 
        transition: background-color 0.2s ease-in-out; 
    }

    .category-link:hover, .category-link:focus {
        text-decoration: none; 
        // background-color: #eaeaea; 
        border-radius: 4px; 
    }

    .category-link i {
        font-size: 20px;
    }
</style>

<div>
    <a href="index.php" class="category-link active">
        <i class="fas fa-arrow-left"></i>
        <span>Back to categories</span>
    </a>
</div>

';
