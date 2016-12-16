package yoshino.support.pili;

import com.qiniu.pili.Client;
import com.qiniu.pili.Hub;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Service;

/**
 * Created by Volio on 2016/12/15.
 */
@Service
@PropertySource("classpath:config/pili.properties")
public class PiliService {

    private Client client;
    private Hub hub;
    private String hubName;
    private String RTMPPublishUrl;
    private String RTMPPlayUrl;
    private String HLSPlayUrl;
    private String HDLPlayUrl;
    private String SnapshotPlayUrl;

    public PiliService(Environment env) {
        this.client = new Client(env.getProperty("accessKey"), env.getProperty("secretKey"));
        this.hubName = env.getProperty("hubName");
        this.hub = this.client.newHub(this.hubName);
        this.RTMPPublishUrl = env.getProperty("RTMPPublishUrl");
        this.RTMPPlayUrl = env.getProperty("RTMPPlayUrl");
        this.HLSPlayUrl = env.getProperty("HLSPlayUrl");
        this.HDLPlayUrl = env.getProperty("HDLPlayUrl");
        this.SnapshotPlayUrl = env.getProperty("SnapshotPlayUrl");
    }

    /**
     * 获取推流地址
     * 有效时间半小时
     * @param streamKey streamKey
     * @return String publishUrl
     */
    public String getPublishUrl(String streamKey) {
        return client.RTMPPublishURL(this.RTMPPublishUrl, this.hubName, streamKey, 1800);
    }
}
