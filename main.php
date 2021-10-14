<?php

require 'vendor/autoload.php';

use Intervention\Image\ImageManager;

// 基础路径
$baseDir = './';
// 输入路径
$inputDir = "{$baseDir}/input/";
// 输出路径
$outputDir = "{$baseDir}/output/";
// 图片类型
$imageExtensions = ['jpg', 'jpeg', 'png'];
// 列数
$columns = 2;
// 行数
$rows = 2;
// 图片填充数量
$pictureSize = 3;
// 画布宽度
$canvasWidth = 1280;
// 画布高度
$canvasHeight = 960;

$blankSize = $columns * $rows;

$imageWidth = $canvasWidth / $columns - 10;

$imageHeight = $canvasHeight / $rows - 10;

$blankPositions = generateBlankPositions($canvasWidth, $canvasHeight, $imageWidth, $imageHeight, $blankSize);

$manager = new ImageManager(['driver' => 'gd']);

$files = array_values(array_filter(scandir($inputDir), function ($file) use ($imageExtensions) {
    return in_array(getFileExtension($file), $imageExtensions);
}));

$fileChunks = array_chunk($files, $pictureSize);

foreach ($fileChunks as $key => $chunk) {

    $image = $manager->canvas($canvasWidth, $canvasHeight);

    $excludeRands = [];

    foreach ($chunk as $index => $file) {
        $insertImage = $manager->make("{$inputDir}/{$file}");

        $insertImage = $insertImage->resize($imageWidth, $imageHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $randomIndex = randIndex(0, $pictureSize, $excludeRands);

        $excludeRands[] = $randomIndex;

        $image->insert($insertImage, 'top-left', $blankPositions[$randomIndex]['x'] + 10, $blankPositions[$randomIndex]['y'] + 10);
    }

    $image->save("{$outputDir}/{$key}.jpg");
}

$fileChunksCount = count($fileChunks);

echo "Total output image: {$fileChunksCount}." . PHP_EOL;