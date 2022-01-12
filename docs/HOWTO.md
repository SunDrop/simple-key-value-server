# HOW TO: internal mechanism

![Workflow](/docs/img/workflow.png)

The main class of the project is **Storage**.

When an instance of **Storage** class is created, auxiliary subclasses are created: 
**MemTable** - initiated by the Shared Memory block (a common block in memory between processes), 
**OpManager** - a subclass that counts the number of operations performed. For every 100 operations (this number is configured in the **OP_LIMIT** class constant), the **MemTable** object is dumped to disk, via the **SSTable** helper class.
Another helper class **RemovedKeysTable** is a shared in-memory table with removed keys. For example, if the key is in the **RemovedKeysTable**, then it is automatically considered deleted and we don't even read the data from the **SSTable**).

## How to work with data

1. Setting a key
   - Key is set to MemTable
   - The key is removed from the RemovedKeysTable
   - If the number of operations is greater than the set limit, then a dump occurs in SSTable

2. Removing a key
   - Key is removed from MemTable
   - The key is set to the RemovedKeysTable

3. Reading a key
   - We are looking for a key in the RemovedKeysTable and, if we find it, we say that there is no data and the key has been deleted
   - We are looking for a key in MemTable and, if we find it, we return the found value
   - We are looking for a key by binary search in SSTable and, if we find it, we return the found value
   - If there is no key in all three repositories, then we say that there is no data for this key.

4. Updating a key
   - It is no different from the key set(key, value) method. If the key is present in both MemTable and SSTable, then "source of truth" will be considered the data in MemTable


## How dump happens in SSTable

Two structures are passed: sorted MemTable data and removed keys RemovedKeysTable
1. We put two pointers, one to MemTable, the second to the old file in SSTable
2. If the required key is in the RemovedKeysTable, then we skip it. It does not get into the new SSTable dump.
3. If the key at the pointer from the SSTable is less (using a string comparison) than the corresponding key in the
4. MemTable, then the key from the SSTable is included in the resulting dump and the SSTable pointer is moved forward.
5. In all other cases, when the keys are equal or the key from the MemTable is less than the key from the SSTable, the key and value from the MemTable are included in the resulting dump, because MemTable value has the highest precedence over SSTable
