<?php

// check the 007 - this is a repeating field
Iterator fields007Iter = fields007.iterator();

List             fields007     = record.getVariableFields("007");
String           leader        = record.getLeader().toString();
ControlField     fixedField008 = (ControlField)     record.getVariableField("008");
DataField        title         = (DataField)        record.getVariableField("245");

char leaderBit;

if (public Set getFormatFacetVZG(Record record) {

  Set result = new LinkedHashSet();

  String format = getFormatVZG(record);

  result.add(format);

  if (format == "Elektronische Bücher") {
    result.add("Bücher");
  }

  if (format == "Elektronische Zeitschriften") {
    result.add("Zeitschriften");
  }

  if (format == "Elektronische Aufsätze") {
    result.add("Aufsätze");
  }

  if ((format == "Elektronische Bücher") || (format == "Elektronische Zeitschriften") || (format == "Elektronische Aufsätze") || (format == "Datenträger")) {
    result.add("Elektronische Ressource");
  }

  if (format == "Bücher") {
    result.add("Gedruckte Bücher");
  }

  if (format == "Aufsätze") {
    result.add("Gedruckte Aufsätze");
  }

  if (format == "Zeitschriften") {
    result.add("Gedruckte Zeitschriften");
  }

  f502 = record.getVariableField("502");
  if(f502 != null) {
    result.add("Hochschulschriften");
  }

  return result;
} != null)
{
  ControlField formatField;
  String formatField007Str = "";
  while(fields007Iter.hasNext())
  {
    formatField007    = (ControlField) fields007Iter.next();
    formatField007Str = formatField.getData();
    formatCode0       = formatField007.getData().toUpperCase().charAt(0);

    #### --- STELLE 0
    switch (formatCode0)
    {
      case 'C': result.add("Data Media"); break;
      case 'D': result.add("Globe"); break;
      case 'F': result.add("Braille"); break;
      case 'G': result.add("Slide"); break;
      case 'H': result.add("Microform");
      case 'K': result.add("Picture");
      case 'M': result.add("MotionPicture"); break;
      case 'O': result.add("Kit"); break;
      case 'Q': result.add("Musical Score"); break;
      case 'R': result.add("SensorImage"); break;
      case 'S': result.add("Sound Recording"); break;
      case 'V': result.add("Video"); break;
    }
  }
}

#---------------------------------------------------------------------------
## check the Leader at position 6
leaderBit6 = leader.charAt(6);
{
  case 'C D': result.add("Musical Score");break;
  case 'E F': result.add("Map"); break;
  case 'G':   result.add("Slide"); break;
  case 'I':   result.add("Sound Recording"); break;
  case 'J':   result.add("Music Recording"); break;
  case 'K':   result.add("Picture"); break;
  case 'M':   result.add("Online Resource"); break;
  case 'O P': result.add("Kit"); break;
  case 'R':   result.add("Physical Object"); break;
  case 'T':   result.add("Manuscript"); break;
}


#---------------------------------------------------------------------------
## check the Leader at position 7     // wenn nicht serial -> buch!
leaderBit7 = leader.charAt(7);
{
  case 'M':  if   (formatCode0 == 'C') { result.add("eBook");    ### < -----------------------------------
             else                      { result.add("Book" ); }  ### < -----------------------------------

  case 'S':   // Look in 008 to determine what type of Continuing Resource   // Serial
       formatCode21 = fixedField008.getData().toUpperCase().charAt(21);
        switch (formatCode21)
        {  case 'N': result.add("Newspaper"); break;
           case 'P': result.add("Journal");   break;
           default:  result.add("Serial");    break;
        }
  default:   // wenn bisher nichts zutrifft, mach ein Buch draus (Besprechung mit Reiner, 19.05.10)

  if(result.isEmpty())
  {
     if (formatCode0 == 'C')  { result.add("eBook"); }    ### < -----------------------------------
     else                     { result.add("Book");  }  ### < -----------------------------------
  }
}

    // Nothing worked!
if (result.isEmpty()) {   result.add("Unknown");  }


if ((result.contains("Journal")) && (result.contains("Online Resource")))
{
  result.add("eJournal");    ### < -----------------------------------
}

return result;
}


public String getFormatVZG(Record record)
{
  String leader = record.getLeader().toString();
  leaderBit6 = leader.charAt(6);
  leaderBit7 = leader.charAt(7);
  List fields007 = record.getVariableFields("007");
  Iterator fields007Iter = fields007.iterator();

  List field902 = record.getVariableFields("951");
  Iterator field902Iter = field902.iterator();

  if (field902 != null)
  {
    while (field902Iter.hasNext())
    {
      fField = (DataField) field902Iter.next();
      if (fField.getSubfield('a') != null)
      {
        if (fField.getSubfield('a').getData().equals("MC"))
        {
          return "Bücher";
          break;
        }
      }
    }
  }

if (fields007 != null)
{
  while (fields007Iter.hasNext())
  {
    fField = (ControlField) fields007Iter.next();
    fCode = fField.getData().toUpperCase().charAt(0);

    if      (fCode == 'V')                                             { return "Filme"; break;        }
    else if (fCode == 'H' && Character.toUpperCase(leaderBit7) != 'S') { return "Mikroformen"; break;  }
  }
}

  switch (Character.toUpperCase(leaderBit6))
  {
    case 'A':
    switch (Character.toUpperCase(leaderBit7))
    {
    case 'M' 'B': return "Bücher"        ; break;
    case 'S'    : return "Zeitschriften" ; break;
        // sepc sagt S für Aufsätze?!? -> Fehler?
    case 'A':
    if (field902 != null)
    {  Iterator field902Iter = field902.iterator();
       while (field902Iter.hasNext())
       {
         fField = (DataField) field902Iter.next();
         if (fField.getSubfield('a') != null)  { if (fField.getSubfield('a').getData().equals("MC")) {  return "Bücher";break;  }
       }
      }
    }

    return "Aufsätze"; break;
    default: return "unbekannt"; break;
    }
    break;

    case 'M':
    switch (Character.toUpperCase(leaderBit7))
    {
        case 'M' 'B': if (fields007 != null)
        {
          while (fields007Iter.hasNext())
          {
            fField = (ControlField) fields007Iter.next();
            if(fField.getData().length() > 1)
            {
              fCode = fField.getData().toUpperCase().charAt(1);
              if (fCode == 'R')
              {
                return "Elektronische Bücher";
                break;
              }
              else if (fCode == 'U')
              {
                return "Datenträger";
                break;
              }
            }
          }
        }

        return "Elektronische Ressource";  break;
        case 'S': return "Elektronische Zeitschriften";  break;
        case 'A': return "Elektronische Aufsätze";  break;
        default:  return "unbekannt"; break;
      }
      break;

    case 'E'        :  return "Karten";  break;
    case 'D' 'F' 'T':  return "Handschriften";  break;
    case 'I' 'J'    :  return "Tonträger";  break;
    case 'R'        :  return "Spiele / Objekte"; break;
    case 'C'        :  return "Noten";  break;

    /*  case 'G':
        // 007 prüfen
        return "Mikroformen";
        break; */
  }

  if (fields007 != null) {
    while (fields007Iter.hasNext()) {
      fField = (ControlField) fields007Iter.next();
      fCode = fField.getData().toUpperCase().charAt(0);
        if (fCode == 'V') {
          return "Filme";
          break;
        }
        else if (fCode == 'H' && leaderBit7 != 'S') {
          return "Mikroformen";
          break;
        }
    }
  }

  return "unbekannt";

}

public String getFormatVZGEng(Record record)
{
  String leader = record.getLeader().toString();
  leaderBit6    = leader.charAt(6);
  leaderBit7    = leader.charAt(7);
  
  List      fields007     = record.getVariableFields("007");
  Iterator  fields007Iter = fields007.iterator();

  List      field902 = record.getVariableFields("951");
  Iterator field902Iter = field902.iterator();

  if (field902 != null) {
    while (field902Iter.hasNext()) {
      fField = (DataField) field902Iter.next();
      if (fField.getSubfield('a') != null) {
        if (fField.getSubfield('a').getData().equals("MC")) {
          return "Book";
          break;
        }
      }
    }
  }

  switch (Character.toUpperCase(leaderBit6)) {

    case 'A':
    switch (Character.toUpperCase(leaderBit7))
    {
        case 'M''B': return "Book"; break;    ### < -----------------------------------
        case 'S': return "Journal"; break;   ### < -----------------------------------
        // sepc sagt S für Aufsätze?!? -> Fehler?
        case 'A':
          if (field902 != null)
          {
            Iterator field902Iter = field902.iterator();
            while (field902Iter.hasNext())
            {
              fField = (DataField) field902Iter.next();
              if (fField.getSubfield('v') != null)
              {
                if (fField.getSubfield('v').getData().equals("Enth"))
                {
                  return "Book";  break;    ### < -----------------------------------
                }
              }
            }
          }
          return "Article";  break;   ### < -----------------------------------
        default: return "unknown"; break;
      }
      break;

    case 'M':
    switch (Character.toUpperCase(leaderBit7))
    {
       case 'M':
       case 'B':
       if (fields007 != null)
       {
         while (fields007Iter.hasNext())
         {
           fField = (ControlField) fields007Iter.next();
           if(fField.getData().length() > 1)
           {
             fCode = fField.getData().toUpperCase().charAt(1);
             if (fCode == 'R')
             {
               return "eBook"; break;     ### < -----------------------------------
             }
             else if (fCode == 'U')
             {
               return "Data Media"; break;
             }
           }
         }
       }
       return "electronic Resource"; break;       ### < -----------------------------------

       case 'S': return "eJournal"; break;    ### < -----------------------------------
       case 'A': return "electronic Article"; break;   ### < -----------------------------------
       default:  return "unknown";  break;    ### < -----------------------------------
      }
      break;

    case 'E': return "Map"; break;

    case 'D':
    case 'F':
    case 'T': return "Manuscript"; break;
    case 'I':
    case 'J': return "Sound Recording"; break;
    case 'R': return "Game"; break;
    case 'C': return "Musical Score"; break;

  }

  if (fields007 != null)
  {
    while (fields007Iter.hasNext())
    {
      fField = (ControlField) fields007Iter.next();
      fCode = fField.getData().toUpperCase().charAt(0);
      if      (fCode == 'V')                                             {  return "Motion Picture"; break; }
      else if (fCode == 'H' && Character.toUpperCase(leaderBit7) != 'S') { return "Microform"; break;  }
    }
  }

  return "unknown";

}





public Set getFormatFacetVZG(Record record) {

  Set result = new LinkedHashSet();

  String format = getFormatVZG(record);

  result.add(format);

  if (format == "Elektronische Bücher") {
    result.add("Bücher");
  }

  if (format == "Elektronische Zeitschriften") {
    result.add("Zeitschriften");
  }

  if (format == "Elektronische Aufsätze") {
    result.add("Aufsätze");
  }

  if ((format == "Elektronische Bücher") || (format == "Elektronische Zeitschriften") || (format == "Elektronische Aufsätze") || (format == "Datenträger")) {
    result.add("Elektronische Ressource");
  }

  if (format == "Bücher") {
    result.add("Gedruckte Bücher");
  }

  if (format == "Aufsätze") {
    result.add("Gedruckte Aufsätze");
  }

  if (format == "Zeitschriften") {
    result.add("Gedruckte Zeitschriften");
  }

  f502 = record.getVariableField("502");
  if(f502 != null) {
    result.add("Hochschulschriften");
  }

  return result;
}




Set getJournalTitle(Record record)
{
  Set result = new LinkedHashSet();
  format = getFormat(record);
  if (format.contains("Journal"))
  {
    List othertitles = record.getVariableFields("246");
    Iterator i = othertitles.iterator();
    while (i.hasNext()) {
      DataField othertitle = (DataField)i.next();
      result.add(othertitle.getSubfield('a').getData());
    }
    DataField title = (DataField) record.getVariableField("245");
    if (title != null) {
      if(title.getSubfield('a') != null) {
        result.add(title.getSubfield('a').getData());
      }

      if(title.getSubfield('b') != null) {
        result.add(title.getSubfield('b').getData());
      }
    }
  }
  return result;
}


