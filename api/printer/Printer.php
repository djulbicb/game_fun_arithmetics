<?php
namespace printer;

class Printer
{
    public static function print_ln($content)
    {
        if (is_array($content))
        {
            Printer::print_tag("p", var_export($content));
        } else if (is_object($content)) {
            Printer::print_tag("p", json_encode($content));
        }
        else
        {
            Printer::print_tag("p", $content);
        }
    }

    public static function print_tag($tag, $content)
    {

        //echo Printer::sprint_tag($tag, $content);
    }

    public static function sprint_tag($tag, $content)
    {
        return "<{$tag}>{$content}</{$tag}>";
    }

    public static function sprint_to_index_table($content)
    {
        $length = 0;

        $firstRowContent = "";
        $secondRowContent = "";

        if (is_array($content))
        {
            $length = count($content);
        }
        else
        {
            $length = strlen($content);
        }

        for ($i = 0;$i < $length;$i++)
        {
            $current = $content[$i];
            $firstRowContent .= Printer::sprint_tag("td", $i);

            if (is_array($current))
            {
                $innerContent = Printer::sprint_to_index_table($current);
                $secondRowContent .= Printer::sprint_tag("td", "{$innerContent}");
            } 
            else
            {
                $secondRowContent .= Printer::sprint_tag("td", $current);
            }
        }

        $firstRowContent = Printer::sprint_tag("tr", $firstRowContent);
        $secondRowContent = Printer::sprint_tag("tr", $secondRowContent);

        $tableContent = "<table border='1' cellpadding='3px' cellspacing='0'>{$firstRowContent} {$secondRowContent}</table>";

        return $tableContent;
    }

    public static function print_to_index_table($content) {
        //echo Printer::sprint_to_index_table($content);
    }
}

