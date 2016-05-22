============
Architecture
============

Hexagonal architecture
======================
One of the main concepts behind the architecture of Arcella is `Hexagonal Architecture`_, which defines several conceptual
layers of code responsibility and helps to decouple code between those layers. It's main purpose is to ensure that our
application expresses itself and uses the Symfony framework to accomplish tasks, instead of being our application
itself.

That results in three differend layers which are:
* `Domain layer`_
* `Application layer`_
* `Framework layer`_

Domain layer
------------
The Domain layer defines the core functionality of Arcella, but it only defines interfaces because it doesn't care about
which implementations the Application layer uses.

Application layer
-----------------


Framework layer
---------------


.. _Hexagonal Architecture: http://fideloper.com/hexagonal-architecture