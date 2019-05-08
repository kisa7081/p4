# Project 4
+ By: Daniel McCullough
+ Production URL: <http://p4.beachboffin.com>

## Feature summary
+ Users can create a conversion on the main page that gets saved to the `conversions` table.
+ Users can navigate to the "Choose Currencies" page to select which currencies they'd like to be able to select in the drop downs.
+ Users can view a history of previous conversions and filter on the "Source Currency" and/or the "Target Currency."
+ On the history page, users can change the conversion rate and recalculate the conversion.  Users may also delete a previous conversion.

## Database summary

+ My application uses 2 tables in total (`conversions`, `currencies`)
+ There's a one-to-many relationship between `conversions` and `currencies`

The `conversions` table joins the `currencies` table by two columns: `source_currency_id` and `target_currency_id`. 

## Outside resources
Just the course notes and PHP docs.

## Code style divergences
I sure hope I didn't diverge.

## Notes for instructor
No notes.  I've really enjoyed this class and look forward to maybe using PHP professionally someday.
