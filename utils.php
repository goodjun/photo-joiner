<?php

function getFileExtension($file)
{
    $fileArray = explode('.', $file);

    if (count($fileArray) === 1) {
        return '';
    }

    return end($fileArray);
}

function getFileName($file)
{
    $fileArray = explode('.', $file);

    return $fileArray[0];
}

function generateBlankPositions($width, $height, $imageWidth, $imageHeight, $blankSize)
{
    $blankPositions = [];
    $currentX = 0;
    $currentY = 0;

    for ($i = 0; $i < $blankSize; $i++) {

        $blankPositions[$i] = [
            'x' => (int)$currentX,
            'y' => (int)$currentY,
        ];

        if ($width >= $currentX + $imageWidth * 2) {
            $currentX += $imageWidth;
        } else if ($height >= $currentY + $imageHeight * 2) {
            $currentX = 0;
            $currentY += $imageHeight;
        }
    }

    return $blankPositions;
}

function randIndex($min, $max, $excludes)
{
    while (true) {
        $number = rand($min, $max);

        if (!in_array($number, $excludes)) {
            return $number;
        }
    }
}