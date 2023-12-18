# Notes/Observations for Part 1

- I've used a standard laravel MVC pattern to handle the form submission and data display.
- For the selling price calculation, I've used the bundled AlpineJS and an API endpoint to calculate the selling price.
- Shipping cost and profit margin have been added to a config file.
- I've used Tailwind CSS for the UI.

This seems to be the most straighforward way to get a shipping price prior to recording the sale, without overcomplicating the solution. 
In general it would be slicker with the use of a Javascript framwork such as Vue  to avoid page refreshes.

I used a short delay on the call to the calculation API, to avoid too many API calls as the user enters data. I chose onKeyUp rather than onBlur, as the user probably won't click/tab out of the field.