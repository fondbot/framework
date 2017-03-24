# Guide for writing tests for FondBot

## Mocks
* Mock all classes which are dependants of class being tested.
* Do not mock database models.
* Use `atLeast()->once()` instead of `once()` in mocks if method is not void.
