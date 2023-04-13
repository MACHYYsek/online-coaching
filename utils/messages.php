<?php

function formatTime(string $createdAt): string {
    try {
        $date = new DateTime($createdAt);
        if ($date->format("Y-m-d") === (new DateTime())->format("Y-m-d")) {
            return $date->format("H:i");
        } else if ($date->format("Y-m-d") === (new DateTime())->sub(new DateInterval("P1D"))->format("Y-m-d")) {
            return "yesterday at " . $date->format("H:i");
        } else {
            return $date->format("d.m.Y H:i");
        }
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}