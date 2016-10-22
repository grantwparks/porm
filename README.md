porm
==

PHP ORM

This is an adaptation of various incarnations.  The goal is to get efficient, robust, expandable property management.  I still don't know if PormModel will need to be your new base class in a hierarchy or if it's something you'll init on the side, passing your instance to it.  To get singleton construction, in particular, either way you'll have to write a static method for construction into your classes wherever you want to use them?  If I impose PormModel as your base class you may have to go to multiple base classes to add it.  If I make it something you call on the side, then you don't have to go down to base classes; you can Porm from whatever level in the hierarchy.

Public managed properties should be designated as @porm in their docblocks.

class PormModel will orchestrate.  Call it from your base class.

class PormProperty will deal with property management.  This includes lazy loading (plus preemptive loading if you code data methods the way we tell you), value caching in session and/or memcached (this needs to be generic), access control, intelligent updates -- only the fields that were actually changed will be saved or appear in audit trail.

class PormCache manages caching of properties.  They can be cached in the session (per user) and/or in Memcache.
