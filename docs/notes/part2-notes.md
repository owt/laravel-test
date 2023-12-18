# Notes/Observations for Part 2

Tagged as v2

Part one has been expanded upon by adding a choice of products. Validation and tests updated to reflect this.

I decided to also store the profit margin as a value in the coffee sales record, as this fixed at a point in time. Theoretically a product's profit margin could change in the future and the historic values would make no sense.

In a real world scenario, there'd be some data migration/config when adding the product column, so the old entries wouldn't contain nulls.  
I didn't cover this as the DB was re-seeded each time, and wanted to concentrate on the immediate user stories.