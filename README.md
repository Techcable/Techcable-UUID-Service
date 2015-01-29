Techcable's UUID Service
======

#Usage

###Name -> UUID

####Java
````java
public static UUID getUUID(String name) {
    BufferedReader reader;
    try {
        URL url = new URL("http://techcable.net/api/uuid/" + name);
        reader = new BufferedReader(new InputStreamReader(url.getInputStream()));
        UUID uuid = UUID.fromString(reader.readLine());
        return uuid;
    } catch (IOException ex) {
        ex.printStackTrace();
        return null;
    } finally {
        try {
            if (reader != null) reader.close();
        } catch (IOException e) {}
    }
}
````

###UUID -> Name
####Java
````java
public static String getName(UUID uuid) {
    BufferedReader reader;
    try {
        URL url = new URL("http://techcable.net/api/name/" + uuid);
        reader = new BufferedReader(new InputStreamReader(url.getInputStream()));
        String name = reader.readLine();
        return name;
    } catch (IOException ex) {
        ex.printStackTrace();
        return null;
    } finally {
        try {
            if (reader != null) reader.close();
        } catch (IOException e) {}
    }
}
````
