## ADDED Requirements

### Requirement: Order approval workflow

The system SHALL deduct inventory when a sales order is approved.

#### Scenario: Approve order successfully

GIVEN a pending sales order exists
AND enough product stock exists

WHEN the user approves the order

THEN the order status becomes approved
AND product stock decreases
AND stock movement is recorded